<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/11/25
 */

namespace Limen\Fastrq;

/**
 * Class Loader
 *
 * @package Limen\Fastrq
 */
class Loader
{
    private static $scripts;

    private static $map = [
        # queue
        'queue_push' => 'queue_push',
        'queue_push_not_in' => 'queue_push_not_in',
        'queue_push_ne' => ['not_exist', 'queue_push'],
        'queue_push_ae' => ['exists', 'queue_push'],
        'queue_pop' => 'queue_pop',
        'capped_queue_push' => 'capped_queue_push',
        'capped_queue_push_ne' => ['not_exist', 'capped_queue_push'],
        'capped_queue_push_ae' => ['exists', 'capped_queue_push'],
        'capped_queue_push_not_in' => 'capped_queue_push_not_in',
        'capped_queue_pop' => 'queue_pop',
        'of_capped_queue_push' => 'of_capped_queue_push',
        'of_capped_queue_push_ne' => ['not_exist', 'of_capped_queue_push'],
        'of_capped_queue_push_ae' => ['exists', 'of_capped_queue_push'],
        'of_capped_queue_push_not_in' => 'of_capped_queue_push_not_in',
        'of_capped_queue_pop' => 'queue_pop',
        'queue_indexof' => 'queue_indexof',
        'capped_queue_indexof' => 'queue_indexof',
        # deque
        'deque_push_back' => 'queue_push',
        'deque_push_back_ne' => 'queue_push_ne',
        'deque_push_back_ae' => 'queue_push_ae',
        'deque_push_back_not_in' => 'queue_push_not_in',
        'deque_push_front' => 'deque_push_front',
        'deque_push_front_ne' => ['not_exist', 'deque_push_front'],
        'deque_push_front_ae' => ['exists', 'deque_push_front'],
        'deque_push_front_not_in' => 'deque_push_front_not_in',
        'deque_pop_back' => 'deque_pop_back',
        'deque_pop_front' => 'queue_pop',
        'capped_deque_push_front' => 'capped_deque_push_front',
        'capped_deque_push_front_ne' => ['not_exist', 'capped_deque_push_front'],
        'capped_deque_push_front_ae' => ['exists', 'capped_deque_push_front'],
        'capped_deque_push_front_not_in' => 'capped_deque_push_front_not_in',
        'capped_deque_push_back' => 'capped_queue_push',
        'capped_deque_push_back_ne' => 'capped_queue_push_ne',
        'capped_deque_push_back_ae' => 'capped_queue_push_ae',
        'capped_deque_push_back_not_in' => 'capped_queue_push_not_in',
        'of_capped_deque_push_front' => 'of_capped_deque_push_front',
        'of_capped_deque_push_front_ne' => ['not_exist', 'of_capped_deque_push_front'],
        'of_capped_deque_push_front_ae' => ['exists', 'of_capped_deque_push_front'],
        'of_capped_deque_push_front_not_in' => 'of_capped_deque_push_front_not_in',
        'of_capped_deque_push_back' => 'of_capped_queue_push',
        'of_capped_deque_push_back_ne' => 'of_capped_queue_push_ne',
        'of_capped_deque_push_back_ae' => 'of_capped_queue_push_ae',
        'of_capped_deque_push_back_not_in' => 'of_capped_queue_push_not_in',
        'of_capped_deque_pop_front' => 'queue_pop',
        'of_capped_deque_pop_back' => 'deque_pop_back',
        'deque_indexof' => 'queue_indexof',
        'capped_deque_indexof' => 'queue_indexof',
        # stack
        'stack_push' => 'stack_push',
        'stack_push_ne' => ['not_exist', 'stack_push'],
        'stack_push_ae' => ['exists', 'stack_push'],
        'stack_push_not_in' => 'stack_push_not_in',
        'stack_pop' => 'stack_pop',
        'capped_stack_push' => 'capped_stack_push',
        'capped_stack_push_ne' => ['not_exist', 'capped_stack_push'],
        'capped_stack_push_ae' => ['exists', 'capped_stack_push'],
        'capped_stack_push_not_in' => 'capped_stack_push_not_in',
        'capped_stack_pop' => 'stack_pop',
        'stack_indexof' => 'queue_indexof',
        # priority queue
        'priority_queue_push' => 'priority_queue_push',
        'priority_queue_push_ne' => ['not_exist', 'priority_queue_push'],
        'priority_queue_push_ae' => ['exists', 'priority_queue_push'],
        'priority_queue_push_not_in' => 'priority_queue_push_not_in',
        'priority_queue_pop' => 'priority_queue_pop',
        'capped_priority_queue_push' => 'capped_priority_queue_push',
        'capped_priority_queue_push_ne' => ['not_exist', 'capped_priority_queue_push'],
        'capped_priority_queue_push_ae' => ['exists', 'capped_priority_queue_push'],
        'capped_priority_queue_push_not_in' => 'capped_priority_queue_push_not_in',
        'capped_priority_queue_pop' => 'priority_queue_pop',
        'of_capped_priority_queue_push' => 'of_capped_priority_queue_push',
        'of_capped_priority_queue_push_ne' => ['not_exist', 'of_capped_priority_queue_push'],
        'of_capped_priority_queue_push_ae' => ['exists', 'of_capped_priority_queue_push'],
        'of_capped_priority_queue_push_not_in' => 'of_capped_priority_queue_push_not_in',
        'priority_queue_indexof' => 'priority_queue_indexof',
        'capped_priority_queue_indexof' => 'priority_queue_indexof',
    ];

    private static function init()
    {
        if (is_null(static::$scripts)) {
            static::$scripts['not_exist'] = <<<LUA
if redis.call('exists',KEYS[1])==1 then
  return 'err_ae'
end 

LUA;

            static::$scripts['exists'] = <<<LUA
if redis.call('exists',KEYS[1])~=1 then
  return 'err_ne'
end

LUA;

            static::$scripts['queue_push'] = <<<LUA
local len
for i,k in ipairs(ARGV) do
  len=redis.call('rpush',KEYS[1],k)
end
return len
LUA;

            static::$scripts['queue_push_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local iv
local i=0
while i<len and iv~=ARGV[1] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[1] then
  len=redis.call('rpush',KEYS[1],ARGV[1])
  y=1
end
return {len,y}
LUA;

            static::$scripts['queue_pop'] = <<<LUA
local o={}
local p=1
local i=0
local c=tonumber(ARGV[1])
while p and i<c do
  p=redis.call('lpop',KEYS[1])
  if p then
    o[#o+1]=p
  end
  i=i+1
end
return o
LUA;

            static::$scripts['capped_queue_push'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + #ARGV - 1 > cap then
  return 'err_qof'
end
for i,k in ipairs(ARGV) do
  if i > 1 then
    len=redis.call('rpush',KEYS[1],k)
  end
end
return len
LUA;

            static::$scripts['capped_queue_push_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len>=cap then
  return 'err_qf'
elseif len+#ARGV-1>cap then
  return 'err_qof'
end
local i=0
local iv
local y=0
while i<len and iv~=ARGV[2] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
if iv~=ARGV[2] then
  len=redis.call('rpush',KEYS[1],ARGV[2])
  y=1
end
return {len,y}
LUA;

            static::$scripts['of_capped_queue_push'] = <<<LUA
local cap=tonumber(ARGV[1])
for i,k in ipairs(ARGV) do
  if i > 1 then
    redis.call('rpush',KEYS[1],k)
  end
end
local len=redis.call('llen',KEYS[1])
local o={}
while len > cap do
  o[#o + 1]=redis.call('lpop',KEYS[1])
  len=len - 1
end
return { len,o }
LUA;

            static::$scripts['of_capped_queue_push_not_in'] = <<<LUA
local cap=tonumber(ARGV[1])
local len=redis.call('llen',KEYS[1])
local i=0
local iv
while i<len and iv~=ARGV[2] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[2] then
  len=redis.call('rpush',KEYS[1],ARGV[2])
  y=1
end
local o={}
while len>cap do
  o[#o+1]=redis.call('lpop',KEYS[1])
  len=len-1
end
return {len,o,y}
LUA;

            static::$scripts['deque_push_front'] = <<<LUA
local len
for i,k in ipairs(ARGV) do
  len=redis.call('lpush',KEYS[1],k)
end
return len
LUA;

            static::$scripts['deque_push_front_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local iv
local i=0
while i<len and iv~=ARGV[1] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[1] then
  len=redis.call('lpush',KEYS[1],ARGV[1])
  y=1
end
return {len,y}
LUA;

            static::$scripts['deque_pop_back'] = <<<LUA
local o={}
for i=1,tonumber(ARGV[1]) do
  o[#o + 1]=redis.call('rpop',KEYS[1])
end
return o
LUA;

            static::$scripts['capped_deque_push_front'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + #ARGV - 1 > cap then
  return 'err_qof'
end
for i,k in ipairs(ARGV) do
  if i > 1 then
    len=redis.call('lpush',KEYS[1],k)
  end
end
return len
LUA;

            static::$scripts['capped_deque_push_front_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + #ARGV - 1 > cap then
  return 'err_qof'
end
local iv
local i=0
local y=0
while i<len and iv~=ARGV[2] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
if iv~=ARGV[2] then
  len=redis.call('lpush',KEYS[1],ARGV[2])
  y=1
end
return {len,y}
LUA;

            static::$scripts['of_capped_deque_push_front'] = <<<LUA
local cap=tonumber(ARGV[1])
for i,k in ipairs(ARGV) do
  if i > 1 then
    redis.call('lpush',KEYS[1],k)
  end
end
local len=redis.call('llen',KEYS[1])
local o={}
while len > cap do
  o[#o + 1]=redis.call('rpop',KEYS[1])
  len=len - 1
end
return { len,o }
LUA;

            static::$scripts['of_capped_deque_push_front_not_in'] = <<<LUA
local cap=tonumber(ARGV[1])
local len=redis.call('llen',KEYS[1])
local iv
local i=0
while i<len and iv~=ARGV[2] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[2] then
  len=redis.call('lpush',KEYS[1],ARGV[2])
  y=1
end
local o={}
while len>cap do
  o[#o+1]=redis.call('rpop',KEYS[1])
  len=len-1
end
return {len,o,y}
LUA;

            static::$scripts['stack_push'] = <<<LUA
local len
for i,k in ipairs(ARGV) do
  len=redis.call('lpush',KEYS[1],k)
end
return len
LUA;

            static::$scripts['stack_push_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local iv
local i=0
while i<len and iv~=ARGV[1] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[1] then
  len=redis.call('lpush',KEYS[1],ARGV[1])
  y=1
end
return {len,y}
LUA;

            static::$scripts['stack_pop'] = <<<LUA
local o={}
local p=1
local i=0
local c=tonumber(ARGV[1])
while p and i<c do
  p=redis.call('lpop',KEYS[1])
  if p then
    o[#o+1]=p
  end
  i=i+1
end
return o
LUA;

            static::$scripts['capped_stack_push'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + #ARGV - 1 > cap then
  return 'err_qof'
end
for i,k in ipairs(ARGV) do
  if i > 1 then
    len=redis.call('lpush',KEYS[1],k)
  end
end
return len
LUA;

            static::$scripts['capped_stack_push_not_in'] = <<<LUA
local len=redis.call('llen',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + #ARGV - 1 > cap then
  return 'err_qof'
end
local iv
local i=0
while i<len and iv~=ARGV[2] do
  iv=redis.call('lindex',KEYS[1],i)
  i=i+1
end
local y=0
if iv~=ARGV[2] then
  len=redis.call('lpush',KEYS[1],ARGV[2])
  y=1
end
return {len,y}
LUA;

            static::$scripts['priority_queue_push'] = <<<LUA
local i=1
while i < #ARGV do
  redis.call('zadd',KEYS[1],ARGV[i],ARGV[i + 1])
  i=i + 2
end
return redis.call('zcard',KEYS[1])
LUA;

            static::$scripts['priority_queue_push_not_in'] = <<<LUA
local y=redis.call('zadd',KEYS[1],'NX',ARGV[1],ARGV[2])
local len=redis.call('zcard',KEYS[1])
return {len,y}
LUA;

            static::$scripts['priority_queue_pop'] = <<<LUA
local h=redis.call('zrange',KEYS[1],0,tonumber(ARGV[1]) - 1,'WITHSCORES')
local i=1
while i < #h do
  redis.call('zrem',KEYS[1],h[i])
  i=i + 2
end
return h
LUA;

            static::$scripts['capped_priority_queue_push'] = <<<LUA
local len=redis.call('zcard',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + (#ARGV - 1) / 2 > cap then
  return 'err_qof'
end
local i=2
while i < #ARGV do
  redis.call('zadd',KEYS[1],ARGV[i],ARGV[i + 1])
  i=i + 2
end
return redis.call('zcard',KEYS[1])
LUA;

            static::$scripts['capped_priority_queue_push_not_in'] = <<<LUA
local len=redis.call('zcard',KEYS[1])
local cap=tonumber(ARGV[1])
if len >= cap then
  return 'err_qf'
elseif len + (#ARGV - 1) / 2 > cap then
  return 'err_qof'
end
local y=redis.call('zadd',KEYS[1],'NX',ARGV[2],ARGV[3])
return {redis.call('zcard',KEYS[1]),y}
LUA;

            static::$scripts['of_capped_priority_queue_push'] = <<<LUA
local cap=tonumber(ARGV[1])
local i=2
while i<#ARGV do
  redis.call('zadd',KEYS[1],ARGV[i],ARGV[i + 1])
  i=i+2
end
local c=redis.call('zcard',KEYS[1])
local o={}
if c>cap then
  o=redis.call('zrange',KEYS[1],cap - c,-1,'WITHSCORES')
  for i,m in ipairs(o) do
    if i % 2 == 1 then
      redis.call('zrem',KEYS[1],m)
      c=c-1
    end
  end
end
return {c,o}
LUA;

            static::$scripts['of_capped_priority_queue_push_not_in'] = <<<LUA
local cap=tonumber(ARGV[1])
local y=0
if not redis.call('zrank',KEYS[1],ARGV[3]) then
  redis.call('zadd',KEYS[1],ARGV[2],ARGV[3])
  y=1
end
local c=redis.call('zcard',KEYS[1])
local o={}
if c>cap then
  local r=redis.call('zrange',KEYS[1],cap - c,-1,'WITHSCORES')
  for i,m in ipairs(r) do
    if i % 2 == 1 then
      redis.call('zrem',KEYS[1],m)
      c=c-1
      if m==ARGV[3] then
        y=0
      else
        o[#o+1]=r[i]
        o[#o+1]=r[i+1]
      end
    end
  end
end
return {c,o,y}
LUA;

            static::$scripts['queue_indexof'] = <<<LUA
local o={}
local len=redis.call('llen',KEYS[1])
for i=1,#ARGV do
    o[i]=-1
    local j=0
    local indv
    while j<len and indv~=ARGV[i] do
        indv=redis.call('lindex',KEYS[1],j)
        if indv==ARGV[i] then
            o[i]=j
        end
        j=j+1
    end
end
return o
LUA;

            static::$scripts['priority_queue_indexof'] = <<<LUA
local o={}
for i=1,#ARGV do
    local r=redis.call('zrank',KEYS[1],ARGV[i])
    if r~=nil then
        o[i]=r
    else
        o[i]=-1
    end
end
return o
LUA;
        }
    }

    /**
     * Load Lua script.
     * To reuse some scripts, the load maybe recursive
     *
     * @param string $command
     *
     * @return bool|string
     * @throws \Exception
     */
    public static function load($command)
    {
        static::init();
        if (!isset(static::$map[$command])) {
            throw new \Exception('Script not found for command:' . $command);
        }
        $real = static::$map[$command];

        if (is_array($real)) {
            $script = '';
            foreach ($real as $frag) {
                $script .= static::$scripts[$frag];
            }

            return $script;
        } elseif (isset(static::$scripts[$real])) {
            return static::$scripts[$real];
        } else {
            return static::load($real);
        }
    }
}