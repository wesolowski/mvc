<?php

declare(strict_types=1);

namespace App\Controller;

final class SmartyView implements ViewInterface
{
    private \Smarty $smarty;

    public function __construct(\Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/../../template');
        $this->smarty->setCompileDir(__DIR__ . '/../../smarty/templates_c');
        $this->smarty->setCacheDir(__DIR__ . '/../../smarty/cache');
        $this->smarty->setConfigDir(__DIR__ . '/../../smarty/configs');
    }

    public function assign(string $key, $value): void
    {
        $this->smarty->assign($key, $value);
    }

    public function displayPage(string $page): void
    {
        try {
            $this->smarty->display($page);
        } catch (\SmartyException $e) {
            exit("Error: {$e}");
        }
    }
}

