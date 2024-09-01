FROM dunglas/frankenphp

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \                  
    libpng-dev \                 
    libjpeg-dev \                
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
