<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class TransactionTest extends TestCase
{
    function setUp() : void {
        parent::setUp();
        DB::delete('delete from categories');
    }
    function testTransactionSuccess() {
        // untuk melakukan beberapa action dalam 1 waktu yang sama, jika terjadi error akan rollback otomatis, jika berhasil akan commit otomatis
        DB::transaction(function () {
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'RMA', 'Real Madrid', 'hala madrid y nada mas!', '2024-04-23 00:00:00'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'BAR', 'Barcelona', 'Puta Barca', '2024-04-23 00:00:00'
            ]);
        },1); //1 disini adalah total attempts yang ingin di execute                                                         
        
        $res = DB::select('select * from categories');
        // dd($res);
        assertCount(2,$res);
    }

    function testTransactionFailed() {
        try {
            // untuk melakukan beberapa action dalam 1 waktu yang sama, jika terjadi error akan rollback otomatis, jika berhasil akan commit otomatis
            DB::transaction(function () {
                DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                    'RMA', 'Real Madrid', 'hala madrid y nada mas!', '2024-04-23 00:00:00'
                ]);
                DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                    'RMA', 'Barcelona', 'Puta Barca', '2024-04-23 00:00:00'
                ]);
            },1); //1 disini adalah total attempts yang ingin di execute                                                         
        } catch (QueryException $e) {

        }
        
        // dd($res);
        $res = DB::select('select * from categories');
        assertCount(0,$res);
    }

    function testManualTransactionSuccess() {
        try {
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'RMA', 'Real Madrid', 'hala madrid y nada mas!', '2024-04-23 00:00:00'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'BAR', 'Barcelona', 'Puta Barca', '2024-04-23 00:00:00'
            ]);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            // throw $th;
        }

        $res = DB::select('select * from categories');
        assertCount(2,$res);
    }

    function testManualTransactionFailed() {
        try {
            DB::beginTransaction();
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'RMA', 'Real Madrid', 'hala madrid y nada mas!', '2024-04-23 00:00:00'
            ]);
            DB::insert('insert into categories(id, name, description, created_at) values (?,?,?,?)', [
                'BAR', 'Barcelona', 'Puta Barca', '2024-04-23 00:00:00'
            ]);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            // throw $th;
        }

        $res = DB::select('select * from categories');
        assertCount(2,$res);
    }

}
