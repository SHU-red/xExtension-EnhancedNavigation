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
    
    public function handleConfigureAction(): void {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $this->setUserConfiguration([
                'show_previous_entry_button' => Minz_Request::paramBoolean('show_previous_entry_button'),
                'show_link_button' => Minz_Request::paramBoolean('show_link_button'),
                'show_up_button' => Minz_Request::paramBoolean('show_up_button'),
                'show_favorite_button' => Minz_Request::paramBoolean('show_favorite_button'),
                'show_next_entry_button' => Minz_Request::paramBoolean('show_next_entry_button'),
            ]);
        }
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
        if ($this->showPreviousEntryButton()) {
            return $this->generateButton('previous_entry', 'gen.action.nav_buttons.prev', 'prev');
        }

        return '';
    }

    private function generateLinkButton(): string {
        if ($this->showLinkButton()) {
            return $this->generateButton('link', 'conf.shortcut.see_on_website', 'link');
        }

        return '';
    }

    private function generateUpButton(): string {
        if ($this->showUpButton()) {
            return $this->generateButton('up', 'gen.action.nav_buttons.up', 'up');
        }

        return '';
    }

    private function generateFavoriteButton(): string {
        if ($this->showFavoriteButton()) {
            return $this->generateButton('favorite', 'conf.shortcut.mark_favorite', 'non-starred');
        }

        return '';
    }

    private function generateNextEntryButton(): string {
        if ($this->showNextEntryButton()) {
            return $this->generateButton('next_entry', 'gen.action.nav_buttons.next', 'next');
        }

        return '';
    }

    public function showPreviousEntryButton(): bool {
        return $this->getUserConfigurationValue('show_previous_entry_button');
    }

    public function showLinkButton(): bool {
        return $this->getUserConfigurationValue('show_link_button');
    }

    public function showUpButton(): bool {
        return $this->getUserConfigurationValue('show_up_button');
    }

    public function showFavoriteButton(): bool {
        return $this->getUserConfigurationValue('show_favorite_button');
    }

    public function showNextEntryButton(): bool {
        return $this->getUserConfigurationValue('show_next_entry_button');
    }
}
