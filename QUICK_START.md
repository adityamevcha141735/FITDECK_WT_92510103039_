# 🚀 Quick Start: Push FITDECK Project to GitHub

## ⚠️ IMPORTANT: Git Installation Required

**Git is not currently installed on your system.** You need to install it first before pushing your project.

---

## Step 1: Install Git (5 minutes)

### Download and Install:
1. **Go to:** https://git-scm.com/download/win
2. **Click:** "Download for Windows"
3. **Run the installer** and follow these steps:
   - ✅ **IMPORTANT:** On the "Select Components" screen, make sure "Git from the command line and also from 3rd-party software" is checked
   - ✅ Keep clicking "Next" with default options
   - ✅ On "Adjusting your PATH environment", select **"Git from the command line and also from 3rd-party software"**
   - ✅ Complete the installation

4. **Restart your terminal/PowerShell** after installation

### Verify Installation:
Open a **NEW** PowerShell window and run:
```powershell
git --version
```
You should see something like: `git version 2.x.x`

---

## Step 2: Push Your Project

### Option A: Automated Script (Easiest) ✅

1. **Right-click** on `push_to_github.bat` in your project folder
2. Select **"Run as administrator"** (or just double-click)
3. Follow the prompts

### Option B: Manual Commands

Open PowerShell in your project folder and run these commands **one by one**:

```powershell
# 1. Initialize Git (if not already done)
git init

# 2. Add remote repository
git remote add origin https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git

# 3. Add all files
git add .

# 4. Create initial commit
git commit -m "Initial commit: FITDECK project"

# 5. Set main branch
git branch -M main

# 6. Push to GitHub
git push -u origin main
```

---

## Step 3: GitHub Authentication

When you run `git push`, you'll be asked for credentials:

### Username:
- Enter your **GitHub username**: `adityamevcha141735`

### Password:
- ⚠️ **DO NOT use your GitHub password**
- You need a **Personal Access Token**

### Create Personal Access Token:

1. Go to: https://github.com/settings/tokens
2. Click **"Generate new token"** → **"Generate new token (classic)"**
3. **Name it:** "FITDECK Project" (or any name)
4. **Expiration:** Choose your preference (90 days, 1 year, or no expiration)
5. **Select scopes:** Check ✅ **`repo`** (Full control of private repositories)
6. Scroll down and click **"Generate token"**
7. **⚠️ COPY THE TOKEN IMMEDIATELY** - you won't see it again!
8. **Paste this token** when Git asks for your password

---

## ✅ Success!

After pushing, your project will be available at:
**https://github.com/adityamevcha141735/FITDECK_WT_92510103039_**

---

## 🔧 Troubleshooting

### "git is not recognized"
- Git is not installed or not in PATH
- **Solution:** Install Git (Step 1) and restart your terminal

### "remote origin already exists"
```powershell
git remote remove origin
git remote add origin https://github.com/adityamevcha141735/FITDECK_WT_92510103039_.git
```

### "Authentication failed"
- Make sure you're using a **Personal Access Token**, not your password
- Verify the token has `repo` permissions

### "Permission denied"
- Check that you have write access to the repository
- Verify your GitHub username is correct

---

## 📝 Future Updates

To push changes later:
```powershell
git add .
git commit -m "Your update message"
git push
```

