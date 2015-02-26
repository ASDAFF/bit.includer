mkdir .last_version
cp -r bit.includer/. .last_version
find .last_version -name '*.php' -exec iconv --verbose -f utf-8 -t windows-1251 -o {} {} \;
zip -r .last_version.zip .last_version
