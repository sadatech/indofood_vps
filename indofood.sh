git add .

echo 'Upd met'
read commitMessage

git commit -m "$commitMessage"

echo 'Upload to master:'
read master

git push --set-upstream origin master

read