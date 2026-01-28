# Script de déploiement pour Windows PowerShell

# Chargement de la configuration locale si elle existe
$ConfigFile = "$PSScriptRoot\deploy.config.ps1"
if (Test-Path $ConfigFile) {
    . $ConfigFile
} else {
    Write-Error "Fichier de configuration '$ConfigFile' introuvable. Veuillez le créer à partir de deploy.config.ps1.dist"
    exit 1
}

Write-Host "Deploying to $SSH_USER@$SSH_HOST..." -ForegroundColor Cyan

# Commande de déploiement
ssh -A "$SSH_USER@$SSH_HOST" "cd $SSH_PATH && git fetch origin && git reset --hard origin/master && make install"

if ($LASTEXITCODE -eq 0) {
    Write-Host "Deployment successful!" -ForegroundColor Green
} else {
    Write-Host "Deployment failed with exit code $LASTEXITCODE" -ForegroundColor Red
}
