# G-Bench [![Build Status](https://travis-ci.com/ggomuk/g-bench.svg?branch=master)](https://travis-ci.org/ggomuk/g-bench)


G-Bench is a nice and  **light** component for `php benchmarking`.

---

### Installation ###
Add this in your `composer.json`

```json
{
    "require": {
        "ggomuk/g-bench": "1.0.0"
    }
}
```

---

### Sample Usage ###

* **start()**, **stop()**
    ```php
    require_once __DIR__ . '/vendor/autoload.php';

    $gb = new \GBench\GBench();

    $gb->start();
    $temp = str_repeat('Hello World!', 10000);
    sleep(2);
    $gb->stop();

    $gb->getDuration(); // 2003 (ms)
    $gb->getMemory(); // 2097152 (bytes)

    echo $gb; // 2.00 MB - 2003 ms
    ```

* **record()**
    ```php
    require_once __DIR__ . '/vendor/autoload.php';

    $gb = new \GBench\GBench();

    $gb->start();

    usleep(500000);
    $temp1 = str_repeat('Hello World!', 10000);

    // You can label records with the record method.
    // The benchmark does not stop as it only makes an intermediate record.
    $gb->record('step1');

    usleep(500000);
    $temp2 = str_repeat('Hello World!', 100000);

    // You can also label records with the stop method.
    $gb->stop('step2');

    // The details of the record can be seen below
    foreach ($gb->getRecords() as $record) {
        $record->getLabel(); // step1, step2
        $record->getMemory(); // 2097152, 2097152
        $record->getDuration(); // 505, 506
        $record->getAccumulateMemory(); // 2097152, 4194304

        echo $record;
        // Usage Memory : 2.00 MB | Accumulate Usage Memory : 2.00 MB | Record Duration : 505 ms -> step1
        // Usage Memory : 2.00 MB | Accumulate Usage Memory : 4.00 MB | Record Duration : 506 ms -> step2
    }
    ```

* **run()**
    ```php
    require_once __DIR__ . '/vendor/autoload.php';

    $gb = new \GBench\GBench();
    $result = $gb->run(function () {
        usleep(500000);
        return str_repeat('Hello World!', 10000);
    }, 'sample_run');

    $gb->getDuration() . "\n"; // 504 (ms)
    $gb->getMemory() . "\n"; // 2097152 (bytes)
    echo $gb . "\n"; // 2.00 MB - 504 ms
    ```

* **Handler**
    ```php
    require_once __DIR__ . '/vendor/autoload.php';

    $logger = new class extends \GBench\AbstractHandler {
        public function startAfter(\GBench\GBench $gbench)
        {
            echo 'good start';
        }

        public function stopAfter(\GBench\GBench $gbench, \GBench\Record $record)
        {
            echo 'good stop';
        }
    };

    $gb = new \GBench\GBench($logger);
    $gb->start(); // good start
    $gb->stop(); // good stop
    ```
