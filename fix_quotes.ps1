$path = "c:\xampp\htdocs\New folder\NexoraLabs\resources\views\orders\create.blade.php"
$content = Get-Content $path -Raw -Encoding UTF8

# Replace curly quotes with straight quotes
$content = $content -replace [char]0x201C, '"'  # Left double quote
$content = $content -replace [char]0x201D, '"'  # Right double quote
$content = $content -replace [char]0x2018, "'"  # Left single quote
$content = $content -replace [char]0x2019, "'"  # Right single quote

Set-Content -Path $path -Value $content -NoNewline -Encoding UTF8
Write-Host "Smart quotes replaced successfully!"
