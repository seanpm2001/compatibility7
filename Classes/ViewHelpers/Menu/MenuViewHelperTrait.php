<?php
namespace TYPO3\CMS\Compatibility7\ViewHelpers\Menu;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * Trait for Menu-ViewHelpers that require support functions for
 * working with menus that require page selection constraints.
 * @deprecated since TYPO3 v8, will be removed in TYPO3 v9
 */
trait MenuViewHelperTrait
{
    /**
     * Get the constraints for the page based on doktype and field "nav_hide"
     *
     * By default the following doktypes are always ignored:
     * - 6: Backend User Section
     * - > 200: Folder (254)
     *          Recycler (255)
     *
     * Optional are:
     * - 199: Menu separator
     * - nav_hide: Not in menu
     *
     * @param bool $includeNotInMenu Should pages which are hidden for menu's be included
     * @param bool $includeMenuSeparator Should pages of type "Menu separator" be included
     * @deprecated since TYPO3 v8, will be removed in TYPO3 v9, use AbstractMenuViewHelper instead of MenuViewHelperTrait
     * @return string
     */
    protected function getPageConstraints($includeNotInMenu = false, $includeMenuSeparator = false)
    {
        GeneralUtility::logDeprecatedFunction();
        $constraints = [];

        $constraints[] = 'doktype NOT IN (' . PageRepository::DOKTYPE_BE_USER_SECTION . ',' . PageRepository::DOKTYPE_RECYCLER . ',' . PageRepository::DOKTYPE_SYSFOLDER . ')';

        if (!$includeNotInMenu) {
            $constraints[] = 'nav_hide = 0';
        }

        if (!$includeMenuSeparator) {
            $constraints[] = 'doktype != ' . PageRepository::DOKTYPE_SPACER;
        }

        return 'AND ' . implode(' AND ', $constraints);
    }

    /**
     * Get a filtered list of page UIDs according to initial list
     * of UIDs and entryLevel parameter.
     *
     * @param array $pageUids
     * @param int|NULL $entryLevel
     * @deprecated since TYPO3 v8, will be removed in TYPO3 v9, use AbstractMenuViewHelper instead of MenuViewHelperTrait
     * @return array
     */
    protected function getPageUids(array $pageUids, $entryLevel = 0)
    {
        GeneralUtility::logDeprecatedFunction();
        $typoScriptFrontendController = $this->getTypoScriptFrontendController();

        // Remove empty entries from array
        $pageUids = array_filter($pageUids);

        // If no pages have been defined, use the current page
        if (!empty($pageUids)) {
            return $pageUids;
        }

        if ($entryLevel === null) {
            return [$typoScriptFrontendController->id];
        }

        if ($entryLevel < 0) {
            $entryLevel = count($typoScriptFrontendController->tmpl->rootLine) - 1 + $entryLevel;
        }
        if (isset($typoScriptFrontendController->tmpl->rootLine[$entryLevel]['uid'])) {
            return [$typoScriptFrontendController->tmpl->rootLine[$entryLevel]['uid']];
        }
        return [];
    }

    /**
     * @param array $variables
     * @deprecated since TYPO3 v8, will be removed in TYPO3 v9, use AbstractMenuViewHelper instead of MenuViewHelperTrait
     * @return mixed
     */
    protected function renderChildrenWithVariables(array $variables)
    {
        GeneralUtility::logDeprecatedFunction();
        foreach ($variables as $name => $value) {
            $this->templateVariableContainer->add($name, $value);
        }

        $output = $this->renderChildren();

        foreach ($variables as $name => $_) {
            $this->templateVariableContainer->remove($name);
        }

        return $output;
    }

    /**
     * @deprecated since TYPO3 v8, will be removed in TYPO3 v9, use AbstractMenuViewHelper instead of MenuViewHelperTrait
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        GeneralUtility::logDeprecatedFunction();
        return $GLOBALS['TSFE'];
    }
}
