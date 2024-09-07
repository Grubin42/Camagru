FROM dunglas/frankenphp

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
 #   msmtp \
    && docker-php-ext-install pdo pdo_pgsql gd\
    && apt-get clean && rm -rf /var/lib/apt/lists/*

#COPY .msmtprc /etc/msmtprc
#RUN chmod 600 /etc/msmtprc

WORKDIR /var/www/html