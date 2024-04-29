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
    function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from products");
        DB::delete("delete from categories");
    }

    function testInsert()
    {
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
        assertEquals(2, $res[0]->total);
    }

    function testSelect()
    {
        $this->testInsert();
        $result = DB::table('categories')->select(['id', 'name', 'description'])->get();
        // dd($result);
        assertNotNull($result);

        $result->each(function ($item) {
            // dd($item); 
            Log::info(json_encode($item));
        });
    }

    function testInsertCategories()
    {
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

    function testWhere()
    {
        $this->testInsertCategories();
        $res = DB::table('categories')->where(function (Builder $builder) {
            $builder->where('id', '=', 'RMA');
            $builder->orWhere('id', '=', 'MUN');
            // SELECT  * FROM categories WHERE (id = RMA OR id = MUN)
        })->get();

        // dd($res);
        assertCount(2, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereBetween()
    {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereBetween('created_at', ["2024-5-1 00:00:00", "2024-12-1 00:00:00"])->get();

        assertCount(3, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereIn()
    {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereIn('id', ["RMA", "MUN"])->get();

        assertCount(2, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereNull()
    {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereNull('description')->get();
        // dd($res);
        assertCount(1, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testWhereDate()
    {
        $this->testInsertCategories();
        $res = DB::table('categories')->whereDate('created_at', '2024-11-24')->get();
        // dd($res);
        assertCount(2, $res);
        $res->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testUpdate()
    {
        $this->testInsertCategories();

        DB::table('categories')->where('id', '=', 'MUN')->update([
            'id' => 'MUTD'
        ]);

        $coll = DB::table('categories')->where('id', '=', 'MUTD')->get();
        assertCount(1, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testUpdateOrInsert()
    {
        $this->testInsertCategories();
        DB::table('categories')->updateOrInsert(['id' => 'MCI'], [
            // "id" => "MCI",  tidak perlu memasukan id ulang, jika data tidak ada otomatis id di atas akan include disini 
            "name" => "Manchester City",
            "description" => "City Till I Die",
            "created_at" => "2024-09-12 21:00:00",
        ]);

        $coll = DB::table('categories')->where('id', '=', 'MCI')->get();
        assertCount(1, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testQueryBuilderIncrement()
    {
        DB::table('counters')->where('id', '=', 'sample')->increment('counter', 1);

        $coll = DB::table('counters')->where('id', '=', 'sample')->get();
        assertCount(1, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testQueryBuilderDelete()
    {
        $this->testInsertCategories();
        DB::table('categories')->where('id', '=', 'MCI')->delete();

        $coll = DB::table('categories')->where('id', '=', 'MCI')->get();
        assertCount(0, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testInsertProducts()
    {
        $this->testInsertCategories();

        DB::table('products')->insert([
            "id" => "1",
            "name" => "Real Madrid, Home Kit 2021/2022",
            "description" => "White kit with a little bit of a blue stripe",
            "price" => 200000,
            "category_id" => "RMA",
        ]);

        DB::table('products')->insert([
            "id" => "2",
            "name" => "Real Madrid, Away Kit 2021/2022",
            "description" => "Dark blue with orange stripe kit",
            "price" => 190000,
            "category_id" => "RMA",
        ]);

        DB::table('products')->insert([
            "id" => "3",
            "name" => "Man United, Home Kit 2023/2024",
            "description" => "Red with black stripe kit",
            "price" => 220000,
            "category_id" => "MUN",
        ]);

        assertTrue(true);
    }

    function testQueryBuilderJoin()
    {
        $this->testInsertProducts();

        $coll = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'categories.name as category_name', 'products.price', 'products.description')
            ->get();

        // dd($coll);

        assertCount(3, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testOrderBy()
    {
        $this->testInsertProducts();

        $coll = DB::table('products')
            ->orderBy('price', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        assertCount(3, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testQueryBuilderPaging()
    {
        $this->testInsertCategories();

        $coll = DB::table('categories')
            ->skip(2)
            ->take(3)
            ->get();

        assertCount(3, $coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testChunk()
    {
        $this->testInsertCategories();

        DB::table('categories')
            ->orderBy('id') //seperti paginate, dia membagi 2 data dari total data [1-2,3-4,4-6]
            ->chunk('2', function ($data) {
                assertNotNull($data);
                Log::info("start");
                $data->each(function ($item) {
                    Log::info(json_encode($item));
                });
                Log::info("end");
            });
    }
        
        
    function testLazy() {
        $this->testInsertCategories();

        $coll = DB::table('categories')
            ->orderBy('id') //seperti paginate, dia membagi 2 data dari total data [1-2,3-4,4-6]
            ->lazy(4) //query hanya di eksekusi jika kita butuh datanya
            ->take(3);

        assertNotNull($coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
            // dd($coll);
    }

    function testCursor() {
        $this->testInsertCategories();

        $coll = DB::table('categories')
        ->orderBy('id')
        ->cursor(); 
        // bedanya cursor dengan chunk dan lazy adalah cursor tidak memiliki limit dan offset, cursor hanya akan melalkukan 1 kali query, lalu semua data akan diambil satu persatu

        assertNotNull($coll);
        $coll->each(function ($item) {
            Log::info(json_encode($item));
        });
    }

    function testAggregates() {
        $this->testInsertProducts();
        $count = DB::table('products')->count('id');
        $max = DB::table('products')->max('price');
        $min = DB::table('products')->min('price');
        $avg = DB::table('products')->avg('price');
        $sum = DB::table('products')->sum('price');

        assertEquals(3,$count);
        assertEquals(220000,$max);
        assertEquals(190000,$min);
        assertEquals(203333.3333,$avg);
        assertEquals(610000,$sum);
    }

    function testRawAggregate() {
        $this->testInsertProducts();
        
        $coll = DB::table('products')->select(
            DB::raw('count(*) as total'),
            DB::raw('max(price) as max'),
            DB::raw('min(price) as min'),
            DB::raw('avg(price) as avg'),
            DB::raw('sum(price) as sum'),
        )->get();

        // dd($coll);

        assertEquals(3,$coll[0]->total);
        assertEquals(220000,$coll[0]->max);
        assertEquals(190000,$coll[0]->min);
        assertEquals(203333.3333,$coll[0]->avg);
        assertEquals(610000,$coll[0]->sum);

    }

    function testGrouping() {
        $this->testInsertProducts();
        
        $coll = DB::table('products')
        ->select('category_id', DB::raw('count(*) as total'))
        ->groupBy('category_id')
        ->orderBy('category_id')
        ->get();

        // dd($coll);
        assertCount(2,$coll);
        assertEquals("MUN", $coll[0]->category_id);
        assertEquals("RMA", $coll[1]->category_id);
        assertEquals(1, $coll[0]->total);
        assertEquals(2, $coll[1]->total);

    }

    function testHaving() {
        $this->testInsertProducts();
        
        $coll = DB::table('products')
        ->select('category_id', DB::raw('count(*) as total'))
        ->groupBy('category_id')
        ->orderBy('category_id')
        ->having('total', '>', '5')
        ->get();

        // dd($coll);
        assertCount(0,$coll);
        // assertEquals("MUN", $coll[0]->category_id);
        // assertEquals("RMA", $coll[1]->category_id);
        // assertEquals(1, $coll[0]->total);
        // assertEquals(2, $coll[1]->total);
    }

    function testLocking() {
        $this->testInsertProducts();

        DB::transaction(function () {
            $coll = DB::table('products')
            ->where('id','=','1')
            // singkatnya, ini tu menghalau adanya perubahan lain ke row ini sebelum transaksi ini selesai dan ter commit
            ->lockForUpdate()
            ->get();
            // dd($coll);
            assertCount(1,$coll);
        });
    }

    function testPagination() {
        $this->testInsertCategories();

        $data = DB::table('categories')->paginate(2);
        // dd($data)
        assertEquals(1, $data->currentPage());
        assertEquals(2, $data->perPage());
        assertEquals(3, $data->lastPage());
        assertEquals(5, $data->total());

        $coll = $data->items();
        // dd($data->items());
        assertCount(2,$coll);
        foreach ($coll as $item) {
            Log::info(json_encode($item));
        }
    }

    function testPaginationIteration() {
        $this->testInsertProducts();

        $page = 1;
        while (true) {
            $paginate = DB::table('categories')->paginate(2,page: $page);
            // dd($paginate);
            if ($paginate->isEmpty()) {
                break;
            } else {
                $page++;
                foreach ($paginate->items() as $item) {
                    assertNotNull($item);
                    Log::info(json_encode($item));
                }
            }
        }
    }

    function testCursorPagination() {
        $this->testInsertProducts();

        $cursor = 'id';
        
        while (true) {
            $paginate = DB::table('categories')->orderBy('id')->cursorPaginate(perPage: 2, cursor: $cursor);
            // dd($paginate->nextCursor());
            // disini aku masih bingung, tapi sepemahamanku , dia ini bukan seperti paginate biasanya yang pake limit, dia ini akan mempainate berdasarkan data terakhir dan akan manggil data setelahnya
            foreach ($paginate->items() as $item) {
                // dd($item);
                assertNotNull($item);
                Log::info(json_encode($item));
            }

            $cursor = $paginate->nextCursor();
            if ($cursor == null) {
                break;
            }
        }
    }
}
