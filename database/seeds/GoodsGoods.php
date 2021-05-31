<?php

use think\migration\Seeder;

class GoodsGoods extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i <= 100; $i ++) {
            $data[] = [
                'id'    => $i,
                'goods_category_id' => 2,
                'name'  => '商品'.$i,
                'sell_point'   => 'Nice'.$i,
                'price'     => ($i + 1)*0.3,
                'num'       => ($i + 2)*3,
                'image'     => '',
                'status'    => random_int(0,1),
                'content'   => '详情'.$i.$i,
                'album'     => ''  
            ];
        }
        $this->table('goods_goods')->insert($data)->save();
    }
}