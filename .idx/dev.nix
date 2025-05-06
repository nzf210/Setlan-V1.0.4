{pkgs}: {
 
  channel = "stable-23.11";
  packages = [
    pkgs.php83
    pkgs.php83Packages.composer
    pkgs.nodejs_20
    pkgs.bun
    # pkgs.ocamlPackages.sail
  ];
  # Sets environment variables in the workspace
  env = {
    shellHook = ''
      # Create .env if it doesn't exist
      if [ ! -f ".env" ]; then
        cat > .env <<EOF
      EOF
      fi
      
      # Install dependencies
      if [ ! -d "vendor" ]; then
        composer install
      fi
      
      # Generate key if not set
      if ! grep -q "^APP_KEY=base64:" .env; then
        php artisan key:generate
      fi
    '';
 
    SESSION_DRIVER="database";
    SESSION_LIFETIME="120";
    SESSION_ENCRYPT="false";
    SESSION_PATH="/";
    SESSION_DOMAIN=null;

    BROADCAST_CONNECTION="log";
    FILESYSTEM_DISK="local";
    QUEUE_CONNECTION="database";
    CACHE_STORE="database";
    VITE_APP_NAME="Setlan Vite";
  };
  idx = {
    # Search for the extensions you want on https://open-vsx.org/ and use "publisher.id"
    extensions = [
      # "vscodevim.vim"
      # "Vue.volar"
    ];
    # Enable previews and customize configuration
   previews = {
    enable = true;
    previews = {
      laravel = {
        command = ["sh" "-c" "composer install && php artisan serve --port 8000 --host 0.0.0.0"];
        manager = "process";
      };
      vue = {
        command = ["sh" "-c" "bun install && bun run dev --port 8001 --host 0.0.0.0"];
        manager = "web";
      };
    };
  };
  };
}
