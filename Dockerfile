# Imagem PHP 8.4 com Apache — última versão estável do PHP
FROM php:8.4-apache

# Instala extensão mysqli usada pela classe conexao
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Habilita OPcache para reduzir parsing/compile do PHP a cada request
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache

# Habilita mod_rewrite (caso alguma página dependa)
RUN a2enmod rewrite

# Ajusta permissões da pasta /tmp (sessão é salva lá pelo index.php)
RUN chmod 1777 /tmp

# Loga erros (não exibe) — exibir antes do session_start envia headers e quebra a sessão
RUN echo "display_errors=Off\nlog_errors=On\nerror_log=/var/log/php_errors.log\nerror_reporting=E_ALL & ~E_DEPRECATED & ~E_NOTICE" > /usr/local/etc/php/conf.d/dev-errors.ini

# Sessão em modo estrito — descarta session IDs desconhecidos/malformados em vez de gerar erro
RUN echo "session.use_strict_mode=1\nsession.cookie_httponly=1\nsession.cookie_samesite=Lax" > /usr/local/etc/php/conf.d/session.ini

# OPcache - cacheia bytecode PHP entre requests para acelerar carga das páginas
RUN echo "opcache.enable=1\nopcache.enable_cli=0\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nopcache.validate_timestamps=1\nopcache.revalidate_freq=2" > /usr/local/etc/php/conf.d/opcache.ini
