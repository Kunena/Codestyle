<?php
/**
 * Kunena Coding Standard
 *
 * @package    Joomla.CodingStandard
 * @copyright  Copyright (C) 2015-2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */
namespace Kunena\Sniffs\NamingConventions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Common;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\NamingConventions\ValidFunctionNameSniff as PEARValidFunctionNameSniff;

/**
 * Extended ruleset for ensuring method and function names are correct.
 *
 * @since     1.0
 */
class ValidFunctionNameSniff extends PEARValidFunctionNameSniff
{
    /**
     * Processes the tokens within the scope.
     *
     * Extends PEAR.NamingConventions.ValidFunctionName.processTokenWithinScope to remove the requirement for leading underscores on
     * private method names.
     *
     * @param File $phpcsFile The file being processed.
     * @param integer $stackPtr The position where this token was found.
     * @param integer $currScope The position of the current scope.
     *
     * @return  void
     */
	protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope)
	{
		$methodName = $phpcsFile->getDeclarationName($stackPtr);

		if ($methodName === null)
		{
			// Ignore closures.
			return;
		}

		$className = $phpcsFile->getDeclarationName($currScope);
		$errorData = array($className . '::' . $methodName);

		// Is this a magic method. i.e., is prefixed with "__" ?
		if (preg_match('|^__[^_]|', $methodName) !== 0)
		{
			$magicPart = strtolower(substr($methodName, 2));

			if (isset($this->magicMethods[$magicPart]) === false)
			{
				$error = 'Method name "%s" is invalid; only PHP magic methods should be prefixed with a double underscore';
				$phpcsFile->addError($error, $stackPtr, 'MethodDoubleUnderscore', $errorData);
			}

			return;
		}

		// PHP4 constructors are allowed to break our rules.
		if ($methodName === $className)
		{
			return;
		}

		// PHP4 destructors are allowed to break our rules.
		if ($methodName === '_' . $className)
		{
			return;
		}

		$methodProps    = $phpcsFile->getMethodProperties($stackPtr);
		$scope          = $methodProps['scope'];
		$scopeSpecified = $methodProps['scope_specified'];

		if ($methodProps['scope'] === 'private')
		{
			$isPublic = false;
		}
		else
		{
			$isPublic = true;
		}

		// Joomla change: Methods must not have an underscore on the front.
		if ($scopeSpecified === true && $methodName[0] === '_')
		{
			$error = '%s method name "%s" must not be prefixed with an underscore';
			$data  = array(
				ucfirst($scope),
				$errorData[0],
			);

			$phpcsFile->addError($error, $stackPtr, 'MethodUnderscore', $data);
			$phpcsFile->recordMetric($stackPtr, 'Method prefixed with underscore', 'yes');

			return;
		}

		/*
		 * If the scope was specified on the method, then the method must be camel caps
		 * and an underscore should be checked for. If it wasn't specified, treat it like a public method
		 * and remove the underscore prefix if there is one because we cant determine if it is private or public.
		 */
		$testMethodName = $methodName;

		if ($scopeSpecified === false && $methodName[0] === '_')
		{
			$testMethodName = substr($methodName, 1);
		}

		if (Common::isCamelCaps($testMethodName, false, true, false) === false)
		{
			if ($scopeSpecified === true)
			{
				$error = '%s method name "%s" is not in camel caps format';
				$data  = array(
					ucfirst($scope),
					$errorData[0],
				);
				$phpcsFile->addError($error, $stackPtr, 'ScopeNotCamelCaps', $data);
			}
			else
			{
				$error = 'Method name "%s" is not in camel caps format';
				$phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $errorData);
			}
        }
	}
}
