#!/bin/sh

# Inicializa o supervisor em segundo plano
/usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisor.conf &

# Aguarda o PostgreSQL ficar disponível
while ! nc -z db 5432; do
  echo "Aguardando o PostgreSQL..."
  sleep 1
done

# Configura o ambiente Laravel
if [ ! -f ".env" ]; then
  echo "Criando arquivo .env..."
  cp .env.example .env
fi

echo "Gerando chave da aplicação..."
php artisan key:generate

echo "Executando migrações..."
php artisan migrate --force --seed

# Mantém o container rodando
tail -f /dev/null
