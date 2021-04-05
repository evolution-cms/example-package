<?php

namespace Database\Seeders;

use EvolutionCMS\Models\{SiteTmplvar, SiteTmplvarTemplate, SiteTemplate};
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    public function run()
    {
        $templateVars = $this->getTemplateVars();
        $templates = $this->getTemplates();

        foreach ($templateVars as $name => $data) {
            // создаем (либо обновляем, если существует) тв-параметр
            $tv = SiteTmplvar::updateOrCreate(['name' => $name], $data);

            // привязываем к шаблонам
            foreach ($templates as $tplid) {
                SiteTmplvarTemplate::updateOrCreate([
                    'tmplvarid'  => $tv->id,
                    'templateid' => $tplid,
                ]);
            }
        }
    }

    protected function getTemplateVars()
    {
        return [
            'price' => [
                'type'    => 'text',
                'caption' => 'Цена',
            ],
            'image' => [
                'type'    => 'image',
                'caption' => 'Изображение',
            ],
        ];
    }

    protected function getTemplates()
    {
        return SiteTemplate::all()
            ->pluck('id', 'templatealias')
            ->toArray();
    }
}
