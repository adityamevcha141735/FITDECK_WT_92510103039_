# PowerShell script to push FITDECK project to GitHub
# Run this script: .\push_to_github.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Git Repository Setup Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if git is available
$gitCheck = Get-Command git -ErrorAction SilentlyContinue
if (-not $gitCheck) {
    Write-Host "✗ ERROR: Git is not installed or not in PATH." -ForegroundColor Red
    Write-Host ""
    Write-Host "Please install Git from: https://git-scm.com/download/win" -ForegroundColor Yellow
    Write-Host "After installation, restart PowerShell and run this script again." -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
} else {
    $gitVersion = git --version 2>&1
    Write-Host "✓ Git found: $gitVersion" -ForegroundColor Green
}

Write-Host ""

# Initialize git repository if not already initialized
if (-not (Test-Path .git)) {
    Write-Host "Initializing Git repository..." -ForegroundColor Yellow
    git init
    if ($LASTEXITCODE -ne 0) {
        Write-Host "✗ Failed to initialize Git repository" -ForegroundColor Red
        Read-Host "Press Enter to exit"
        exit 1
    }
    Write-Host "✓ Git repository initialized" -ForegroundColor Green
} else {
    Write-Host "✓ Git repository already initialized" -ForegroundColor Green
}
Write-Host ""

# Add remote repository
Write-Host "Setting up remote repository..." -ForegroundColor Yellow
$remoteUrl = "https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git"

# Remove existing origin if it exists
git remote remove origin 2>$null

# Add new origin
git remote add origin $remoteUrl
if ($LASTEXITCODE -ne 0) {
    Write-Host "✗ Failed to add remote repository" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host "✓ Remote 'origin' set to: $remoteUrl" -ForegroundColor Green
Write-Host ""

# Add all files
Write-Host "Adding all files to staging..." -ForegroundColor Yellow
git add .
if ($LASTEXITCODE -ne 0) {
    Write-Host "✗ Failed to add files" -ForegroundColor Red
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host "✓ Files added to staging" -ForegroundColor Green
Write-Host ""

# Commit changes
Write-Host "Committing changes..." -ForegroundColor Yellow
git commit -m "Initial commit: FITDECK project"
if ($LASTEXITCODE -ne 0) {
    Write-Host "⚠ Warning: Commit failed or no changes to commit" -ForegroundColor Yellow
    Write-Host "This might be normal if files were already committed." -ForegroundColor Yellow
}
Write-Host ""

# Set branch to main
Write-Host "Setting branch to 'main'..." -ForegroundColor Yellow
git branch -M main 2>$null
Write-Host ""

# Push to repository
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Ready to push to GitHub!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "You will be prompted for:" -ForegroundColor Yellow
Write-Host "  • Username: adityamevcha141735" -ForegroundColor White
Write-Host "  • Password: Use a Personal Access Token (NOT your GitHub password)" -ForegroundColor White
Write-Host ""
Write-Host "Need to create a token? Visit:" -ForegroundColor Yellow
Write-Host "  https://github.com/settings/tokens" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press any key to continue with push..." -ForegroundColor Yellow
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

Write-Host ""
Write-Host "Pushing to GitHub..." -ForegroundColor Yellow
git push -u origin main

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "✓ Success! Project pushed to GitHub!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "View your repository at:" -ForegroundColor Cyan
    Write-Host "https://github.com/adityamevcha141735/FITDECK_WT_92510103039_" -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "✗ Push failed. Common issues:" -ForegroundColor Red
    Write-Host "  • Authentication: Use Personal Access Token, not password" -ForegroundColor Yellow
    Write-Host "  • Network: Check your internet connection" -ForegroundColor Yellow
    Write-Host "  • Permissions: Verify you have access to the repository" -ForegroundColor Yellow
}

Write-Host ""
Read-Host "Press Enter to exit"

