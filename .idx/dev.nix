# To learn more about how to use Nix to configure your environment
# see: https://developers.google.com/idx/guides/customize-idx-env
{pkgs}: {
  # Which nixpkgs channel to use.
  channel = "stable-23.11"; # or "unstable"
  # Use https://search.nixos.org/packages to find packages
  packages = [
    pkgs.php82
    pkgs.php82Packages.composer
    pkgs.nodejs_20
    pkgs.bun
    # pkgs.ocamlPackages.sail
  ];
  # Sets environment variables in the workspace
  env = {
    APP_NAME="Setlan";
    APP_ENV="local";
    APP_KEY=base64:wDtrkGH9cGbXou4veMdrnubEe/iRQDtRorZj/jHKCpk=;
    APP_DEBUG=true;
    APP_TIMEZONE="UTC";
    APP_URL=https://9002-idx-setlan-1716105265281.cluster-nx3nmmkbnfe54q3dd4pfbgilpc.cloudworkstations.dev/;
    #* POSTGRES
    DB_CONNECTION="pgsql";
    DB_HOST="aws-0-ap-southeast-1.pooler.supabase.com";
    DB_PORT=5432;
    DB_DATABASE="postgres";
    DB_USERNAME="postgres.tjwztuqmxzffhokhkmjs";
    DB_PASSWORD="vTJuzUKr9QWUtYR2";

    SESSION_DRIVER="database";
    SESSION_LIFETIME=120;
    SESSION_ENCRYPT=false;
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
        web = {
          command = ["php" "artisan" "serve" "--port" "$PORT" "--host" "0.0.0.0"];
          manager = "web";
        };
      };
    };
  };
}
