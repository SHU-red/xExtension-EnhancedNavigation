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
        return <<<NAV
            <nav id="nav_entries_enhanced">
                {$this->generatePreviousEntryButton()}
                {$this->generateLinkButton()}
                {$this->generateUpButton()}
                {$this->generateFavoriteButton()}
                {$this->generateNextEntryButton()}
            </nav>
            NAV;
    }

    private function generateButton(string $class, string $title, string $icon): string {
        $title = _t($title);
        $icon = _i($icon);

        return "<button class=\"{$class}\" title=\"{$title}\">{$icon}</button>";
    }

    private function generatePreviousEntryButton(): string {
        return $this->generateButton('previous_entry', 'gen.action.nav_buttons.prev', 'prev');
    }

    private function generateLinkButton(): string {
        return $this->generateButton('link', 'conf.shortcut.see_on_website', 'link');
    }

    private function generateUpButton(): string {
        return $this->generateButton('up', 'gen.action.nav_buttons.up', 'up');
    }

    private function generateFavoriteButton(): string {
        return $this->generateButton('favorite', 'conf.shortcut.mark_favorite', 'non-starred');
    }

    private function generateNextEntryButton(): string {
        return $this->generateButton('next_entry', 'gen.action.nav_buttons.next', 'next');
    }
}
