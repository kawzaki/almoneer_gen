<?php

namespace App\Support;

class ArabicNumber
{
    /**
     * Format a count with proper Arabic grammar rules (تمييز العدد المعدود).
     *
     * Rules:
     * - 0: 0 + جمع
     * - 1: مفرد + واحد / واحدة
     * - 2: مثنى
     * - 3..10: عدد + جمع مجرور (مثلاً: 4 أعوام، 5 وسوم، 4 محاضرات)
     * - 11..99: عدد + مفرد منصوب (مثلاً: 14 عاماً، 44 وسماً، 12 محاضرةً)
     * - 100, 200, 1000.. ومضاعفاتها: عدد + مفرد مجرور (مثلاً: 100 عام، 100 وسم)
     * - فوق 100: يتبع التمييز آخر رقمين (العشرات والآحاد).
     *
     * @param int $count
     * @param string $singular         (مثال: عام، وسم، محاضرة)
     * @param string $dual             (مثال: عامان، وسمان، محاضرتان)
     * @param string $plural3to10      (مثال: أعوام، وسوم، محاضرات)
     * @param string $accusative11to99 (مثال: عاماً، وسماً، محاضرةً)
     * @param string|null $hundreds    (مثال: عام، وسم، محاضرة)
     * @param bool $includeNumber
     * @return string
     */
    public static function format(
        int $count,
        string $singular,
        string $dual,
        string $plural3to10,
        string $accusative11to99,
        ?string $hundreds = null,
        bool $includeNumber = true
    ): string {
        $hundreds = $hundreds ?? $singular;

        if ($count === 0) {
            return $includeNumber ? "0 {$plural3to10}" : $plural3to10;
        }

        if ($count === 1) {
            return "{$singular} واحد";
        }

        if ($count === 2) {
            return "{$dual}";
        }

        $rem = $count % 100;

        if ($count >= 100 && $rem === 0) {
            $word = $hundreds;
        } elseif ($rem >= 3 && $rem <= 10) {
            $word = $plural3to10;
        } elseif ($rem >= 11 && $rem <= 99) {
            $word = $accusative11to99;
        } elseif ($rem === 1) {
            $word = $singular;
        } elseif ($rem === 2) {
            $word = $dual;
        } else {
            $word = $plural3to10;
        }

        return $includeNumber ? "{$count} {$word}" : $word;
    }

    /**
     * تمييز الأعوام والسنوات: عام واحد، عامان، 4 أعوام، 14 عاماً، 100 عام
     */
    public static function years(int $count, bool $includeNumber = true): string
    {
        return self::format($count, 'عام', 'عامان', 'أعوام', 'عاماً', 'عام', $includeNumber);
    }

    /**
     * تمييز الكلمات الدلالية والوسوم: وسم واحد، وسمان، 4 وسوم، 44 وسماً، 100 وسم
     */
    public static function tags(int $count, bool $includeNumber = true): string
    {
        return self::format($count, 'وسم', 'وسمان', 'وسوم', 'وسماً', 'وسم', $includeNumber);
    }

    /**
     * تمييز المواضيع: موضوع واحد، موضوعان، 4 مواضيع، 44 موضوعاً، 100 موضوع
     */
    public static function topics(int $count, bool $includeNumber = true): string
    {
        return self::format($count, 'موضوع', 'موضوعان', 'مواضيع', 'موضوعاً', 'موضوع', $includeNumber);
    }

    /**
     * تمييز المحاضرات: محاضرة واحدة، محاضرتان، 4 محاضرات، 14 محاضرةً، 100 محاضرة
     */
    public static function lectures(int $count, bool $includeNumber = true): string
    {
        return self::format($count, 'محاضرة', 'محاضرتان', 'محاضرات', 'محاضرةً', 'محاضرة', $includeNumber);
    }

    /**
     * تمييز المواد والدروس: مادة واحدة، مادتان، 4 مواد، 14 مادةً، 100 مادة
     */
    public static function items(int $count, bool $includeNumber = true): string
    {
        return self::format($count, 'مادة', 'مادتان', 'مواد', 'مادةً', 'مادة', $includeNumber);
    }
}
