<?php
/**
 * Kunena Coding Standard
 *
 * @package    Joomla.CodingStandard
 * @copyright  Copyright (C) 2015-2019 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

/**
 * SingleCommentUnitTest
 *
 * @since     1.0
 */
class Kunena_Tests_Commenting_SingleCommentUnitTest extends AbstractSniffUnitTest
{
	/**
	 * Returns the lines where errors should occur.
	 *
	 * The key of the array should represent the line number and the value
	 * should represent the number of errors that should occur on that line.
	 *
	 * @return array<int, int>
	 */
	public function getErrorList()
	{
		return array(
				13 => 1,
				20 => 1,
				27 => 1,
				34 => 1,
				45 => 1,
				53 => 1,
				63 => 1,
				68 => 1,
				75 => 1,
				88 => 1,
			   );
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * The key of the array should represent the line number and the value
	 * should represent the number of warnings that should occur on that line.
	 *
	 * @return array<int, int>
	 */
	public function getWarningList()
	{
		return array();
	}
}
