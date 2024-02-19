del tomax-base-moodle.zip
mkdir tomax
copy * tomax
Xcopy  /S /I /E classes  tomax\classes
Xcopy  /S /I /E db  tomax\db
Xcopy  /S /I /E lang  tomax\lang
rmdir tomax\tomax /s /q
tar.exe -a -c -f tomax-base-moodle.zip tomax
rmdir tomax /s /q