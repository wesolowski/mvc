<?php

declare(strict_types=1);

namespace App\Core\View;

final class SmartyView implements ViewInterface
{
    private \Smarty $smarty;
    private array $params = [];
    private string $template = "error.tpl";

    public function __construct(\Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->smarty->setTemplateDir(__DIR__ . '/../../../template');
        $this->smarty->setCompileDir(__DIR__ . '/../../../smarty/templates_c');
        $this->smarty->setCacheDir(__DIR__ . '/../../../smarty/cache');
        $this->smarty->setConfigDir(__DIR__ . '/../../../smarty/configs');
    }

    public function addTlpParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }

    public function addTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function display(): void
    {
        try {
            $this->smarty->assign($this->params);
            $this->smarty->display($this->template);
        } catch (\SmartyException $e) {
            exit("Error: {$e}");
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}

