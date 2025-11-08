@echo off
echo ========================================
echo Git Repository Setup Script
echo ========================================
echo.

REM Check if git is available
where git >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Git is not installed or not in PATH.
    echo Please install Git from: https://git-scm.com/download/win
    echo After installation, restart this script.
    pause
    exit /b 1
)

echo Git found! Proceeding with repository setup...
echo.

REM Initialize git repository if not already initialized
if not exist .git (
    echo Initializing Git repository...
    git init
    echo.
)

REM Add remote repository
echo Setting up remote repository...
git remote remove origin 2>nul
git remote add origin https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git
echo Remote 'origin' set to: https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git
echo.

REM Add all files
echo Adding all files to staging...
git add .
echo.

REM Commit changes
echo Committing changes...
git commit -m "Initial commit: FITDECK project"
echo.

REM Push to repository
echo Pushing to GitHub...
echo Note: You may be prompted for your GitHub credentials.
git branch -M main
git push -u origin main

echo.
echo ========================================
echo Done! Check your repository at:
echo https://github.com/adityamevcha141735/FITDECK_WT_92510103039_
echo ========================================
pause

