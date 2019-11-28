# 来自 `30 seconds of knowledge` 相关PHP代码片段

### 部分片段展示

>   这里只列出部分代码片段，完整的请查阅：https://github.com/30-seconds/30_seconds_of_knowledge/blob/master/src/assets/snippets/php/ 。


<details>
  <summary>`average` - 求平均数</summary>
```php
function average(...$items) {
    $count = count($items);
    return $count === 0 ? 0 : array_sum($items) / $count;
}
average(1, 2, 3); // 2
```
</details>

<details>
  <summary>`deepFlatten` - 混维数组平展成一维数组</summary>
```php
function deepFlatten($items) {
    $result = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            $result[] = $item;
        } else {
            $result = array_merge($result, deepFlatten($item));
        }
    }
    return $result;
}
deepFlatten([1, [2], [[3], 4], 5]); // [1, 2, 3, 4, 5]
```
</details>

<details>
  <summary>`endsWith` - 是否以某个特定词结尾</summary>
```php
function endsWith($haystack, $needle) {
    return strrpos($haystack, $needle) === (strlen($haystack) - strlen($needle));
}
endsWith('Hi, this is me', 'me'); // true
```
</details>

<details>
  <summary>`factorial` - 阶乘计算</summary>
```php
function factorial($n) {
    if ($n <= 1) {
        return 1;
    }
    return $n * factorial($n - 1);
}
factorial(6); // 720
```
</details>

<details>
  <summary>`fibonacci` - 斐波那契数列</summary>
```php
function fibonacci($n) {
    $sequence = [0, 1];
    for ($i = 2; $i < $n; $i++) {
        $sequence[$i] = $sequence[$i-1] + $sequence[$i-2];
    }
    return $sequence;
}
fibonacci(6); // [0, 1, 1, 2, 3, 5]
```
</details>

<details>
  <summary>`gcd` - 最大公约数计算</summary>
```php
function gcd(...$numbers) {
    if (count($numbers) > 2) {
        return array_reduce($numbers, 'gcd');
    }
    $r = $numbers[0] % $numbers[1];
    return $r === 0 ? abs($numbers[1]) : gcd($numbers[1], $r);
}
gcd(8, 36); // 4
gcd(12, 8, 32); // 4
```
</details>

<details>
  <summary>`head` - 取得首元素</summary>
```php
function head($items) {
    return reset($items);
}
head([1, 2, 3]); // 1
```
</details>

<details>
  <summary>`isPrime` - 质数判定</summary>
```php
function isPrime($number) {
    $boundary = floor(sqrt($number));
    for ($i = 2; $i <= $boundary; $i++) {
        if ($number % $i === 0) {
            return false;
        }
    }
    return $number >= 2;
}
isPrime(3); // true
```
</details>

<details>
  <summary>`last` - 取得尾元素</summary>
```php
function last($items) {
    return end($items);
}
last([1, 2, 3]); // 3
```
</details>

<details>
  <summary>`lcm` - 最小公倍数计算</summary>
```php
function lcm(...$numbers) {
    $ans = $numbers[0];
    for ($i = 1, $max = count($numbers); $i < $max; $i++) {
        $ans = (($numbers[$i] * $ans) / gcd($numbers[$i], $ans));
    }
    return $ans;
}
lcm(12, 7); // 84
lcm(1, 3, 4, 5); // 60
```
</details>

<details>
  <summary>`palindrome` - 回文判断</summary>
```php
function palindrome($string) {
    return strrev($string) === (string) $string;
}
palindrome('racecar'); // true
palindrome(2221222); // true
```
</details>

<details>
  <summary>`startsWith` - 是否以某个特定词开始</summary>
```php
function startsWith($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}
startsWith('Hi, this is me', 'Hi'); // true
```
</details>