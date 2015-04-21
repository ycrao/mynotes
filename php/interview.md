# PHP面试题汇总

>    部分题目收集自网络，里面可能会穿插一些MySQL与HTML相关问题。

## 1. echo(),print(),print_r()的区别？

**echo** 和 **print** 不是一个函数，是一个语言结构；  
`print(string $arg)` 只有一个参数；  
`echo arg1,arg2` 可以输出多个参数，返回 `void` ；  
`echo` 和 `print` 只能打印出string，不能打印出结构；  
`print_r`能打印出结构。比如:
```php
$arr = array("key"=>"value");
print_r($arr);
```

## 2. 语句include和require的区别是什么?

在失败的时候：  
`include` 产生一个 `warning` ，而 `require` 直接产生错误中断；  
`require` 在运行前载入；  
`include` 在运行时载入；  
`require_once` 和 `include_once` 可以避免重复包含同一文件。  

## 3. php中传值与传引用有啥区别?

&表示传引用；  
函数中参数传引用会将参数进行改变；  
一般在输出参数有多个的时候可以考虑使用引用。  

```php
$num = 10
function multiply($num){
    $num = $num * 10;
}
multiply($num);
echo $num;
```

## 4. 下面哪项没有将john添加到users数组中？

```
(a) $users[] = 'john';
(b) array_add($users,'john');
(c) array_push($users,'john');
(d) $users ||= 'john';
```
答案为bd，php 里面无 `array_add` 函数，d项为语法错误的表达。

## 5. HTTP协议中几个状态码的含义。

```
200 : 请求成功，请求的数据随之返回。
301 : 永久性重定向。
302 : 暂时行重定向。
401 : 当前请求需要用户验证。
403 : 服务器拒绝执行请求，即没有权限。
404 : 请求失败，请求的数据在服务器上未发现。
500 : 服务器错误。一般服务器端程序执行错误。
503 : 服务器临时维护或过载。这个状态时临时性的。
```
## 6. 写出一些php魔术方法。

```
__construct() 实例化类时自动调用。
__destruct() 类对象使用结束时自动调用。
__set() 在给未定义的属性赋值的时候调用。
__get() 调用未定义的属性时候调用。
__isset() 使用isset()或empty()函数时候会调用。
__unset() 使用unset()时候会调用。
__sleep() 使用serialize序列化时候调用。
__wakeup() 使用unserialize反序列化的时候调用。
__call() 调用一个不存在的方法的时候调用。
__callStatic()调用一个不存在的静态方法是调用。
__toString() 把对象转换成字符串的时候会调用。比如 echo。
__invoke() 当尝试把对象当方法调用时调用。
__set_state() 当使用var_export()函数时候调用。接受一个数组参数。
__clone() 当使用clone复制一个对象时候调用。
```

## 7. MySQL存储引擎 MyISAM 和 InnoDB 的区别。

```
a. MyISAM类型不支持事务处理等高级处理，而InnoDB类型支持.
b. MyISAM类型的表强调的是性能，其执行数度比InnoDB类型更快.
c. InnoDB不支持FULLTEXT类型的索引.
d. InnoDB中不保存表的具体行数，也就是说，执行select count(*) from table时，InnoDB要扫描一遍整个表来计算有多少行，但是MyISAM只要简单的读出保存好的行数即可.
e. 对于AUTO_INCREMENT类型的字段，InnoDB中必须包含只有该字段的索引，但是在MyISAM表中，可以和其他字段一起建立联合索引。
f. DELETE FROM table时，InnoDB不会重新建立表，而是一行一行的删除。
g. LOAD TABLE FROM MASTER操作对InnoDB是不起作用的，解决方法是首先把InnoDB表改成MyISAM表，导入数据后再改成InnoDB表，但是对于使用的额外的InnoDB特性(例如外键)的表不适用.
h. MyISAM支持表锁，InnoDB支持行锁。
```

## 8. 说出一些MySQL优化方法？

```
a. 设计良好的数据库结构，允许部分数据冗余，尽量避免join查询，提高效率。
b. 选择合适的表字段数据类型和存储引擎，适当的添加索引。
c. mysql库主从读写分离。
d. 找规律分表，减少单表中的数据量提高查询速度。
e。添加缓存机制，比如memcached，apc等。
f. 不经常改动的页面，生成静态页面。
g. 书写高效率的SQL。比如 SELECT * FROM TABEL 改为 SELECT field_1, field_2, field_3 FROM TABLE.
```
## 9. 下面$a的结果是：
```php
<?php
$a = in_array('01', array('1')) == var_dump('01' == 1);
?>
```
A true   
B false  

答案为A

## 10. 说下php中empty()和isset()的区别。

`isset` 用于检测变量是否被设置，使用 `isset()` 测试一个被设置成 NULL 的变量，将返回 `FALSE` 。  
`empty` 如果 `var` 是非空或非零的值，则 `empty()` 返回 `FALSE`。换句话说，`""、0、"0"、NULL、FALSE、array()、var $var;` 以及没有任何属性的对象都将被认为是空的，如果 `var` 为空，则返回 `TRUE` 。

如果变量为 `0` ，则`empty()`会返回`TRUE`，`isset()`会返回`TRUE`；  
如果变量为空字符串，则`empty()`会返回TRUE，`isset()`会返回`TRUE`；  
如果变量未定义，则`empty()`会返回`TRUE`，`isset()`会返回`FLASE`。
   
注意：`isset()` 只能用于变量，因为传递任何其它参数都将造成解析错误。若想检测常量是否已设置，可使用 `defined()` 函数。
当要 判断一个变量是否已经声明的时候 可以使用 `isset` 函数；  
当要 判断一个变量是否已经赋予数据且不为空 可以用 `empty `函数；  
当要 判断 一个变量 存在且不为空 先 `isset` 函数 再用 `empty` 函数；  

