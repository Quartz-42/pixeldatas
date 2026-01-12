# Script de déploiement pour Windows PowerShell
$SSH_USER = "kina8534"
$SSH_HOST = "trefle.o2switch.net"
$SSH_PATH = "/home/kina8534/pixeldatas"

Write-Host "Deploying to $SSH_USER@$SSH_HOST..." -ForegroundColor Cyan

ssh -A "$SSH_USER@$SSH_HOST" "cd $SSH_PATH && git pull origin master && make install"

if ($LASTEXITCODE -eq 0) {
    Write-Host "Deployment successful!" -ForegroundColor Green
} else {
    Write-Host "Deployment failed with exit code $LASTEXITCODE" -ForegroundColor Red
}
