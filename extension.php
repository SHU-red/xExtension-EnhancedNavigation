<?php

declare(strict_types=1);

class EnhancedNavigationExtension extends Minz_Extension {
    private const FILENAME = 'style.css';

    public function init(): void {
        $this->registerTranslates();

        if (version_compare(FRESHRSS_VERSION, '1.28') >= 0) {
            $this->registerHook(Minz_HookType::NavEntries, [$this, 'generateEnhancedNavigation']);
        } else {
            $this->registerHook('nav_entries', [$this, 'generateEnhancedNavigation']);
        }

        Minz_View::appendStyle($this->getFileUrl('navigation.css', 'css'));
        Minz_View::appendScript($this->getFileUrl('navigation.js', 'js'));
        if ($this->hasFile(self::FILENAME)) {
            Minz_View::appendStyle($this->getFileUrl(self::FILENAME, isStatic: false));
        }
    }
    
    public function handleConfigureAction(): void {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $this->setUserConfiguration([
                'show_previous_entry_button' => Minz_Request::paramBoolean('show_previous_entry_button'),
                'show_see_on_website_button' => Minz_Request::paramBoolean('show_see_on_website_button'),
                'show_up_button' => Minz_Request::paramBoolean('show_up_button'),
                'show_favorite_button' => Minz_Request::paramBoolean('show_favorite_button'),
                'show_next_entry_button' => Minz_Request::paramBoolean('show_next_entry_button'),
            ]);
            $this->saveFile(self::FILENAME, <<<CSS
                #nav_entries_enhanced button {
                    width: {$this->computeButtonWidth()}%;
                }
                CSS);
        }
    }

    private function computeButtonWidth(): int {
        $activeButtons = array_intersect_assoc([
            'show_previous_entry_button' => true,
            'show_see_on_website_button' => true,
            'show_up_button' => true,
            'show_favorite_button' => true,
            'show_next_entry_button' => true,
        ], $this->getUserConfiguration());

        return (int)(100 / count($activeButtons));
    }

    public function generateEnhancedNavigation(): string {
        return <<<NAV
            <nav id="nav_entries_enhanced">
                {$this->generatePreviousEntryButton()}
                {$this->generateSeeOnWebsiteButton()}
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

    private function generateSeeOnWebsiteButton(): string {
        if ($this->showSeeOnWebSiteButton()) {
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
        return (bool) ($this->getUserConfigurationValue('show_previous_entry_button') ?? true);
    }

    public function showSeeOnWebSiteButton(): bool {
        return (bool) ($this->getUserConfigurationValue('show_see_on_website_button') ?? true);
    }

    public function showUpButton(): bool {
        return (bool) ($this->getUserConfigurationValue('show_up_button') ?? true);
    }

    public function showFavoriteButton(): bool {
        return (bool) ($this->getUserConfigurationValue('show_favorite_button') ?? true);
    }

    public function showNextEntryButton(): bool {
        return (bool) ($this->getUserConfigurationValue('show_next_entry_button') ?? true);
    }
}
