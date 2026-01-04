<ul class="space-y-2 font-medium">
  <livewire:side-item title="Dashboard" href="/" />
  <livewire:side-item title="table" href="/table" />
  <livewire:side-item title="User Management" icon="users" :collapsable="true" :items="[
                        ['title' => 'List Users', 'href' => '/users'],
                        ['title' => 'Roles', 'href' => '/roles'],
                        ['title' => 'Permissions', 'href' => '/permissions']
                    ]" />
  <livewire:side-item title="User Management 2" icon="users" :collapsable="true" :items="[
                        ['title' => 'List Users 2', 'href' => '/users'],
                        ['title' => 'Roles 2', 'href' => '/roles'],
                        ['title' => 'Permissions 2', 'href' => '/permissions']
                    ]" />
</ul>