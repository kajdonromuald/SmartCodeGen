@echo off
title Java Backend Indítása Maven segítségével
cd /d %~dp0

echo ===============================
echo Maven alapú Java szerver indítása
echo ===============================
echo.

REM Ellenőrizzük, hogy van-e pom.xml
IF NOT EXIST pom.xml (
    echo ❌ Hiba: Nem található pom.xml a mappában!
    pause
    exit /b 1
)

REM A szerver indítása és naplózás
echo ▶ Szerver indítása...
mvn clean spring-boot:run

REM Várakozás, hogy látható maradjon a kimenet
echo.
echo ===============================
echo A szerver futása befejeződött vagy leállt.
echo Nyomj meg egy billentyűt a kilépéshez...
pause >nul
