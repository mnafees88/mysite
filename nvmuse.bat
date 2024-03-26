
IF EXIST "C:\Users\%USERNAME%\nodejs" (

    rmdir "C:\Users\%USERNAME%\nodejs"
)
mklink /j "C:\Users\%USERNAME%\nodejs" "%NVM_HOME%\v%1"