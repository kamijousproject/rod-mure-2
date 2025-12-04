<?php
/**
 * BaseModel - Base class for all models with common CRUD operations
 */

namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    
    /**
     * Find record by ID
     */
    public static function find(int $id): ?array
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        return Database::fetch(
            "SELECT * FROM {$table} WHERE {$pk} = ?",
            [$id]
        );
    }
    
    /**
     * Find record by column
     */
    public static function findBy(string $column, mixed $value): ?array
    {
        $table = static::$table;
        
        return Database::fetch(
            "SELECT * FROM {$table} WHERE {$column} = ?",
            [$value]
        );
    }
    
    /**
     * Get all records
     */
    public static function all(string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $table = static::$table;
        
        return Database::fetchAll(
            "SELECT * FROM {$table} ORDER BY {$orderBy} {$direction}"
        );
    }
    
    /**
     * Create new record
     */
    public static function create(array $data): int
    {
        $table = static::$table;
        $fillable = static::$fillable;
        
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($fillable));
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        Database::query(
            "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );
        
        return (int)Database::lastInsertId();
    }
    
    /**
     * Update record
     */
    public static function update(int $id, array $data): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        $fillable = static::$fillable;
        
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($fillable));
        
        if (empty($data)) {
            return false;
        }
        
        $sets = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;
        
        Database::query(
            "UPDATE {$table} SET {$sets} WHERE {$pk} = ?",
            $values
        );
        
        return true;
    }
    
    /**
     * Delete record
     */
    public static function delete(int $id): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        Database::query(
            "DELETE FROM {$table} WHERE {$pk} = ?",
            [$id]
        );
        
        return true;
    }
    
    /**
     * Count records
     */
    public static function count(string $where = '', array $params = []): int
    {
        $table = static::$table;
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = Database::fetch($sql, $params);
        return (int)($result['total'] ?? 0);
    }
    
    /**
     * Check if record exists
     */
    public static function exists(string $column, mixed $value, ?int $exceptId = null): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        $sql = "SELECT COUNT(*) as total FROM {$table} WHERE {$column} = ?";
        $params = [$value];
        
        if ($exceptId !== null) {
            $sql .= " AND {$pk} != ?";
            $params[] = $exceptId;
        }
        
        $result = Database::fetch($sql, $params);
        return ($result['total'] ?? 0) > 0;
    }
    
    /**
     * Paginate records
     */
    public static function paginate(int $page = 1, int $perPage = 12, string $where = '', array $params = [], string $orderBy = 'id DESC'): array
    {
        $table = static::$table;
        $offset = ($page - 1) * $perPage;
        
        // Count total
        $countSql = "SELECT COUNT(*) as total FROM {$table}";
        if ($where) {
            $countSql .= " WHERE {$where}";
        }
        $total = (int)(Database::fetch($countSql, $params)['total'] ?? 0);
        
        // Get records
        $sql = "SELECT * FROM {$table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $sql .= " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        
        $items = Database::fetchAll($sql, $params);
        
        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
            'has_more' => ($page * $perPage) < $total,
        ];
    }
}
