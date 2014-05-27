<?php
namespace Craft;

class TruncateTwigExtension extends \Twig_Extension
{

	public function getName()
	{
		return Craft::t('Truncate');
	}

	public function getFilters()
	{
		return array(
			'truncate' => new \Twig_Filter_Method($this, 'truncateFilter')
		);
	}

	public function truncateFilter($str, $delimiter='chars', $limit='150', $ending='', $stripHTML=false)
	{
		// Can we handle multibyte strings?
		$mb_ok = function_exists('mb_get_info');

		// Get our Twig charset
		$charset = craft()->templates->getTwig()->getCharset();

		// Strip HTML from the string
		if ($stripHTML == true) {
			$str = strip_tags($str);
		}

		// Work with the text
		switch ($delimiter)
		{
			// Are we counting by words?
			case 'words':
				if (str_word_count($str, 0) > $limit)
				{
					$words = str_word_count($str, 2);
					$pos   = array_keys($words);
					$str   = ($mb_ok) ? mb_substr($str, 0, $pos[$limit], $charset) : substr($str, 0, $pos[$limit]);
				}
				else{
					return $str;
				}
				break;

			// Default to counting by chars
			default:
				if (strlen($str) < $limit) {
					return $str;
				}
				$str = ($mb_ok) ? mb_substr($str, 0, $limit, $charset) : substr($str, 0, $limit);
				break;
		}

		return rtrim($str).$ending;
	}

}
