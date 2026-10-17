<?php
declare(strict_types=1);

class Category
{
    public function __construct(
        public string $category,
        public int $id = 0          // 0 until the DBMS assigns one
    ) {}

    /** @return true|int|string true on success, 1062 for duplicate name, or error text */

    public function intoDb(): true|int|string
    {
        try {
            $pdo = Tools::connect();
            $ps = $pdo->prepare('INSERT INTO categories (category) VALUES (:category)');
            $ps->execute(['category' => $this->category]);

            $this->id = (int) $pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // 1062 = MySQL duplicate-key error (category already exists)
            return ($e->errorInfo[1] ?? 0) === 1062 ? 1062 : $e->getMessage();
        }
    }

    public static function fromDb(int $id): ?static
    {
        $ps = Tools::connect()->prepare('SELECT * FROM categories WHERE id = ?');
        $ps->execute([$id]);
        $row = $ps->fetch();

        return $row === false
            ? null
            : new static($row['category'], (int) $row['id']);
    }

    /** @return list<Category> every category, oldest first */

    public static function getCategories(): array
    {
        $ps = Tools::connect()->query('SELECT * FROM categories ORDER BY id');

        $categories = [];
        while ($row = $ps->fetch()) {
            $categories[] = new static($row['category'], (int) $row['id']);
        }
        return $categories;
    }

    /** @return int how many rows were actually deleted */

    public static function deleteByIds(array $ids): int
    {
        try {
            $ps = Tools::connect()->prepare('DELETE FROM categories WHERE id = ?');

            $deleted = 0;
            foreach ($ids as $id) {
                $ps->execute([(int) $id]);
                $deleted += $ps->rowCount();
            }
            return $deleted;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
}
