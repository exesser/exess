<?php declare(strict_types=1);

namespace Test\CmsBundle\Functional\ExEss\Cms\Dashboard\Model;

use Test\CmsBundle\Helper\Testcase\FunctionalTestCase;
use ExEss\Bundle\CmsBundle\Dashboard\Model\Grid;

class GridTest extends FunctionalTestCase
{
    public function testCreateModel(): void
    {
        $grids = $this->tester->grabAllFromDatabase('grid_gridtemplates', 'key_c, json_fields_c');

        foreach ($grids as $grid) {
            $key = $grid['key_c'];
            try {
                $gridModel = new Grid($grid['json_fields_c']);
            } catch (\Exception $e) {
                throw new \Exception($key . ' can\'t be constructed: ' . $e->getMessage());
            }
            $this->tester->assertInstanceOf(Grid::class, $gridModel, $key . ' is invalid');
        }
    }
}
