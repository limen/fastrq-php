# Fastrq - Queue, Stack and Priority Queue based on Redis

[![Build Status](https://travis-ci.org/limen/fastrq-php.svg?branch=master)](https://travis-ci.org/limen/fastrq-php)

[Wiki](https://github.com/limen/fastrq/wiki)

[Fastrq for Python](https://github.com/limen/fastrq)

## Features

+ Abstract Queue, Deque, Capped Queue/Deque, and Overflow-able Capped Queue/Deque
+ Abstract Stack, Capped Stack
+ Abstract Priority Queue, Capped Priority Queue and Overflow-able Capped Priority Queue
+ Push and Pop support batch operation
+ Using Lua scripts to save RTT (Round Trip Time)

more in [Wiki](https://github.com/limen/fastrq/wiki)

## install

Recommend to install via composer

```
composer require limen/fastrq
```

## Usage

```php
use Limen\Fastrq\Queue;
use Limen\Fastrq\Deque;
use Limen\Fastrq\Stack;
use Limen\Fastrq\PriorityQueue;

// queue
$q = new Queue('fastrq-queue');
$q->push(['hello', 'world']);
$q->push('!!');
$q->pop();
$q->pop(2);
$q->pushNI('hello');
$q->pushAE('from');
$q->pushNE(['from', 'fastrq']);

// deque
$dq = new Deque('fastrq-deque');
$dq->pushFront(['hello', 'world']);
$dq->pushBack('!!');
$dq->popFront();
$dq->popBack(2);
$dq->pushFrontNI('hello');
$dq->pushBackNI('hello');
$dq->pushFrontNE('from');
$dq->pushBackNE(['from', 'fastrq']);
$dq->pushFrontAE('from');
$dq->pushBackAE(['from', 'fastrq']);

// stack
$s = new Stack('fastrq-stack');
$s->push(['hello', 'world']);
$s->push('!!');
$s->pop();
$s->pop(2);
$s->pushNI('hello');
$s->pushAE('from');
$s->pushNE(['from', 'fastrq']);

// priority queue
$pq = new PriorityQueue('fastrq-priority-queue');
$pq->push(['hello' => 1]);
$pq->push(['hello' => 1, 'world' => 2]);
$pq->pushNI('fastrq', 2);
$pq->pushAE(['hello' => 1, 'world' => 2]);
$pq->pushNE(['hello' => 1, 'world' => 2]);

```

## Data types

### Queue

+ first in and first out
+ unlimited capacity
+ support batch push and batch pop

### Deque

Derive from queue with more features

+ support push front and push back
+ support pop front and pop back

### Capped Queue/Deque

Derive from queue/deque with more features

+ Have fixed capacity
+ Push to a full one would fail
+ Push to one whose positions are not enough would fail

### Overflow-able Capped Queue/Deque

Derive from capped queue/deque with more features

+ The queue length would never exceed its capacity
+ Push to an end would push out from the other end if one is full

### Stack 

+ Last in and First out
+ Unlimited capacity
+ Support batch push and batch pop

### Capped Stack

Derive from Stack with more features

+ Have fixed capacity
+ Push to a full capped stack would fail
+ Push to a capped stack whose positions are not enough would fail

### Priority Queue

+ The lower the score, the higher the priority
+ Unlimited capacity
+ Support batch push and batch pop

### Capped Priority Queue

Derive from Priority Queue with more features

+ Have fixed capacity
+ Push to a full one would fail
+ Push to a capped one whose positions are not enough would fail

### Overflow-able Capped Priority Queue

Derive from Capped Priority Queue with more features

+ The queue length would never exceed its capacity
+ Push to an end would push out from the other end if queue is full


