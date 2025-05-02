import { RouteParam, RouteParamsWithQueryOverload } from 'ziggy-js';
import { PageProps } from '@inertiajs/core';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    route: (
      name?: string,
      params?: RouteParam | RouteParamsWithQueryOverload,
      absolute?: boolean
    ) => Zyggy.Route;
  }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
      $page: {
        props: PageProps & {
          errors: Record<string, string>;
          auth: {
            user: {
              name: string;
              email: string;
            };
          };
        };
      };
    }
  }
