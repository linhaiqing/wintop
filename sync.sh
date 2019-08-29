mysqldump -uroot -proot wintop > /home/wwwroot/wintop/wintop.sql
git add -A
git commit -m 'backup'
git push
git pull

