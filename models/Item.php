<?php
declare(strict_types=1);

class Item
{
    public function __construct(
        public string $itemname,
        public int $catid,
        public int $pricein,
        public int $pricesale,
        public string $info,
        public  string $imagepath,
        public float $rate = 0,
        public int $action = 0,
        public int $id = 0
    ) {}

    /** @return true|int|string true on success, 1062 for duplicate login, or error text */

    public function intoDb(): true|int|string
    {
        try {
            $ps = Tools::connect()->prepare(
                'INSERT INTO items (itemname, catid, pricein, pricesale, info, imagepath, rate, action)
                 VALUE (:itemname, :catid, :pricein, :pricesale, :info, :imagepath, :rate, :action)'
            );
            $ps->execute([
                'itemname' => $this->itemname,
                'catid' => $this->catid,
                'pricein' => $this->pricein,
                'pricesale' => $this->pricesale,
                'info' => $this->info,
                'imagepath' => $this->imagepath,
                'rate' => $this->rate,
                'action' => $this->action
            ]);
            return true;
        } catch (PDOException $e) {
            // 1062 = MySQL duplicate-key error (login already taken)
            return ($e->errorInfo[1] ?? 0) === 1062 ? 1062 : $e->getMessage();
        }
    }

    public static function fromDb(int $id): ?static
    {
        $ps = Tools::connect()->prepare('SELECT * FROM items WHERE id = ?');
        $ps->execute([$id]);
        $row = $ps->fetch();

        return $row === false
            ? null
            : new static($row['itemname'], $row['catid'], $row['pricein'], $row['pricesale'], $row['info'], $row['imagepath'], $row['rate'], $row['action'], $row['id']);
    }

    /** @return list<Item> all items, or only one cateogry when $catid > 0 */

    public static function getItems(int $catid = 0): array
    {
        $pdo = Tools::connect();

        if ($catid === 0) {
            $ps = $pdo->prepare('SELECT * FROM items');
            $ps->execute();
        } else {
            $ps = $pdo->prepare('SELECT * FROM items WHERE catid = ?');
            $ps->execute([$catid]);
        }

        $items = [];
        while ($row = $ps->fetch()) {
            $items[] = new Item(
                $row['itemname'], (int)$row['catid'],
                (int)$row['pricein'], (int)$row['pricesale'],
                $row['info'], $row['imagepath'],
                (float)$row['rate'], (int)$row['action'],
                $row['id']
            );
        }
        return $items;
    }

    public static function getAllItems(): array
    {
        $pdo = Tools::connect();

        $ps = $pdo->query('SELECT * FROM items ORDER BY id')->fetchAll();

        $items = [];

        foreach($ps as $row) {
            $items[] = new static(
                $row['itemname'], (int)$row['catid'],
                (int)$row['pricein'], (int)$row['pricesale'],
                $row['info'], $row['imagepath'],
                (float)$row['rate'], (int)$row['action'],
                $row['id']
            );
        }
        
        return $items;
    }

    public static function updateItemImg(int $id, string $imagepath): true|int|string {
        try {
            $pdo = Tools::connect();

            $ps = $pdo->prepare('UPDATE items set imagepath = :imagepath WHERE id = :id');
            $ps->execute([
                'imagepath' => $imagepath,
                'id'        => $id
            ]);
            return true;
        } catch (PDOException $e) {
            return ($e->errorInfo[1] ?? 0) === 1062 ? 1062 : $e->getMessage();
        }
    }

    // A schematic item card - design your own version
    public function draw(): void
    {
        $name = htmlspecialchars($this->itemname);
        $info = htmlspecialchars($this->info);
        $img = htmlspecialchars($this->imagepath);

        echo <<<HTML
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">
                        <a href="pages/iteminfo.php?id={$this->id}" target="_blank" class="text-decoration-none">{$name}</a>
                    </h5>
                    <p class="text-center text-secondary">{$this->rate}&nbsp;rate</p>
                    <div class="rounded">
                        <img src="{$img}" alt="$name" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    </div>
                    <h4 class="text-success text-center mt-1">\$&nbsp;{$this->pricesale}</h4>
                    <p class="text-secondary text-center">{$info}</p>
                    <button class="btn btn-primary w-100" onclick="addToCart({$this->id})">Add To My Cart</button>
                </div>
            </div>
        </div>
        HTML;
    }

    public function sale(): void
    {
        $pdo = Tools::connect();
        $ruser = $_SESSION['reg'] ?? 'cart';

        // increase the customer's total...
        $pdo->prepare('UPDATE customers SET total = total + ? WHERE login = ?')
            ->execute([$this->pricesale, $ruser]);

        // ...record the sale..
        $pdo->prepare('INSERT INTO sales (customername, itemname, pricein, pricesale, datesale)
                        VALUES(?, ?, ?, ?, ?)')
            ->execute([$ruser, $this->itemname, $this->pricein, $this->pricesale, new DateTimeImmutable()->format('Y-m-d')]);

        // ...and remove the sold item
        $pdo->prepare('DELETE FROM items WHERE id = ?')->execute([$this->id]);
    }
}