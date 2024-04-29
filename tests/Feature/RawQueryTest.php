<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class RawQueryTest extends TestCase
{
    function setUp() : void {
        parent::setUp();
        DB::delete("delete from products");
        DB::delete('delete from categories');
    }

    function testCrud() {
        DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
            'RMA', 'Real Madrid', 'hala madrid y nada mas!', '2024-04-23 00:00:00'
        ]);

        $res = DB::select('select * from categories where id = ?', ['RMA']);
        // dd($res);

        assertCount(1, $res);
        assertEquals('RMA', $res[0]->id);
        assertEquals('Real Madrid', $res[0]->name);
        assertEquals('hala madrid y nada mas!', $res[0]->description);
        assertEquals('2024-04-23 00:00:00', $res[0]->created_at);
    }

    function testNamedBinding() {
        DB::insert('insert into categories(id, name, description, created_at) values (:id, :name, :description, :created_at)', [
            "id" => "RMA",
            "name" => "Real Madrid",
            "description" => "vamos",
            "created_at" => "2024-04-23 00:00:00",
        ]);

        $res = DB::select('select * from categories where id = :id', ['id' => 'RMA']);
        
        assertCount(1, $res);
        assertEquals('RMA', $res[0]->id);
        assertEquals('Real Madrid', $res[0]->name);
        assertEquals('vamos', $res[0]->description);
        assertEquals('2024-04-23 00:00:00', $res[0]->created_at);
    }
}
