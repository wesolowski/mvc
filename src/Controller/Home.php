<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;

final class Home implements ControllerInterface
{
    private ViewInterface $smartyController;

    public function __construct(ViewInterface $smartyController){
        $this->smartyController = $smartyController;
    }

    public function action(ProductRepository $pr): void
    {
        $pr_list = $pr->getList();

        $table = "<table border='1'>";
        foreach ($pr_list as $key_1 => $value_1){
            $table .= "<tr>";
            foreach ($pr_list[$key_1] as $value_2){
                $table .= "<td>" . $value_2 . "</td>";
            }
            $table .= "<td><a href='index.php?page=Detail&id=".urlencode(htmlspecialchars($pr_list[$key_1]['id']))."'>Detail</a>";
            $table .= "</tr>";
        }
        $table .= "</table>";

        $this->smartyController->addTlpParam('content', $table);
        $this->smartyController->addTemplate('home.tpl');
    }
}