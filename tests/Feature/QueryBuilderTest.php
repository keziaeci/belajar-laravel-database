<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use Illuminate\Foundation\Testing\WithFaker;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

use Illuminate\Foundation\Testing\RefreshDatabase;

class QueryBuilderTest extends TestCase
{
    function setUp(): void {
        parent::setUp();
        DB::delete("delete from categories");
    }

    function testInsert() {
        DB::table('categories')->insert([
            "id" => "RMA",
            "name" => "Real Madrid",
            "description" => "hala madrid y nada mas",
            "created_at" => "2024-04-24 00:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "BAR",
            "name" => "Bayern Munchen",
            "description" => "munchen jaya jaya jaya",
            "created_at" => "2024-04-24 00:00:00",
        ]);

        $res = DB::select('select count(id) as total from categories');
        // dd($res);
        assertEquals(2,$res[0]->total);
    }

    function testSelect() {
        $this->testInsert();
        $result = DB::table('categories')->select(['id','name','description'])->get();
        // dd($result);
        assertNotNull($result);

        $result->each(function ($item) {
            // dd($item); 
            Log::info(json_encode($item));
        });
    }

    function testInsertCategories() {
        DB::table('categories')->insert([
            "id" => "RMA",
            "name" => "Real Madrid",
            "description" => "hala madrid y nada mas",
            "created_at" => "2024-04-24 00:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "BAR",
            "name" => "Bayern Munchen",
            "description" => "mia san mia mama mia lezatos",
            "created_at" => "2024-09-24 00:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "MUN",
            "name" => "Manchester United",
            "description" => "Glory Glory Man United",
            "created_at" => "2024-12-24 21:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "JUV",
            "name" => "Juventus",
            "description" => "Fino alla Fine",
            "created_at" => "2024-11-24 10:00:00",
        ]);
        DB::table('categories')->insert([
            "id" => "ACM",
            "name" => "AC Milan",
            "created_at" => "2024-11-24 10:00:00",
        ]);

        assertTrue(true);
    }

    function testWhere() {
        $this->testInsertCategories();
        $res = DB::table('categories')->where(function (Builder $builder) {
            $builder->where('id','=','RMA');
            $builder->orWhere('id', '=', 'MUN');
            // SELECT  * FROM categories WHERE (id = RMA OR id = MUN)
        })->get();

        // dd($res);
        assertCount(2,$res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
    
    function testWhereBetween() {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereBetween('created_at',["2024-5-1 00:00:00", "2024-12-1 00:00:00"])->get();
        
        assertCount(3, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
    
    function testWhereIn() {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereIn('id',["RMA", "MUN"])->get();
        
        assertCount(2, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereNull() {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereNull('description')->get();
        // dd($res);
        assertCount(1, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereDate() {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereDate('created_at','2024-11-24')->get();
        // dd($res);
        assertCount(2, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
    
    function testUpdate()  {
        $this->testInsertCategories();
        
        DB::table('categories')->where('id', '=' , 'MUN')->update([
            'id' => 'MUTD'
        ]);
        
        $coll = DB::table('categories')->where('id','=','MUTD')->get();
        assertCount(1,$coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }
    
    function testUpdateOrInsert()  {
        $this->testInsertCategories();
        DB::table('categories')->updateOrInsert(['id' => 'MCI'], [
            // "id" => "MCI",  tidak perlu memasukan id ulang, jika data tidak ada otomatis id di atas akan include disini 
            "name" => "Manchester City",
            "description" => "City Till I Die",
            "created_at" => "2024-09-12 21:00:00",
        ]);
        
        $coll = DB::table('categories')->where('id','=','MCI')->get();
        assertCount(1,$coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item)); 
        });
    }
    
    function testQueryBuilderIncrement() {
        DB::table('counters')->where('id','=','sample')->increment('counter',1);
        
        $coll = DB::table('counters')->where('id','=','sample')->get();
        assertCount(1,$coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item)); 
        });
    }
    
    function testQueryBuilderDelete() {
        $this->testInsertCategories();
        DB::table('categories')->where('id','=','MCI')->delete();
        
        $coll = DB::table('categories')->where('id','=','MCI')->get();
        assertCount(0,$coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item)); 
        });
    }
}
