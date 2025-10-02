<?php

declare(strict_types=1);

class EnhancedNavigationExtension extends Minz_Extension {
    public function init(): void {
        $this->registerTranslates();

        if (version_compare(FRESHRSS_VERSION, '1.28') >= 0) {
            $this->registerHook(Minz_HookType::NavEntries, [$this, 'generateEnhancedNavigation']);
        } else {
            $this->registerHook('nav_entries', [$this, 'generateEnhancedNavigation']);
        }

        Minz_View::appendStyle($this->getFileUrl('navigation.css', 'css'));
        Minz_View::appendScript($this->getFileUrl('navigation.js', 'js'));
    }
    
    public function generateEnhancedNavigation(): string {
        return include 'navigation.phtml';
    }
}
