#定时任务1秒执行一次,模仿长进程,新的进程需要手动添加。例:$(/usr/local/php/bin/php '/www/wlz_phplib/queue/pop.php')
#!/bin/bash
step=1 #间隔的秒数，不能大于60
for (( i = 0; i < 60; i=(i+step) )); do
  $(/usr/local/php/bin/php '/www/wlz_phplib/queue/pop.php')
  $(/usr/local/php/bin/php '/www/wlz_phplib/queue/pop2.php')
  sleep $step
done
exit 0