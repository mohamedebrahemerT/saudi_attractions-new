{{--<li class="{{ Request::is('locales*') ? 'active' : '' }}">--}}
    {{--<a href="{!! route('locales.index') !!}"><i class="fa fa-edit"></i><span>Locales</span></a>--}}
{{--</li>--}}

<li class="{{ Request::is('settings*') ? 'active' : '' }}">
    <a href="{!! route('settings.index') !!}"><i class="fa fa-edit"></i><span>Settings</span></a>
</li>

<li class="{{ Request::is('categories*') ? 'active' : '' }}">
    <a href="{!! route('categories.index') !!}"><i class="fa fa-edit"></i><span>Categories</span></a>
</li>

<li class="{{ Request::is('subCategories*') ? 'active' : '' }}">
    <a href="{!! route('subCategories.index') !!}"><i class="fa fa-edit"></i><span>Sub Categories</span></a>
</li>

<li class="{{ Request::is('events*') ? 'active' : '' }}">
    <a href="{!! route('events.index') !!}"><i class="fa fa-edit"></i><span>Events</span></a>
</li>

<li class="{{ Request::is('orders') ? 'active' : '' }}">
    <a href="{!! route('orders.index') !!}"><i class="fa fa-edit"></i><span>Events' Orders</span></a>
</li>

<li class="{{ Request::is('orders/approved*') ? 'active' : '' }}">
    <a href="{!! route('orders.approved_orders') !!}"><i class="fa fa-edit"></i><span>Approved Events' Orders</span></a>
</li>

<li class="{{ Request::is('socialMedia*') ? 'active' : '' }}">
    <a href="{!! route('socialMedia.index') !!}"><i class="fa fa-edit"></i><span>Social Media</span></a>
</li>

<li class="{{ Request::is('users') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-edit"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('users/admins*') ? 'active' : '' }}">
    <a href="{!! route('users.admins') !!}"><i class="fa fa-edit"></i><span>Admins</span></a>
</li>

<li class="{{ Request::is('attractions*') ? 'active' : '' }}">
    <a href="{!! route('attractions.index') !!}"><i class="fa fa-edit"></i><span>Attractions</span></a>
</li>

<li class="{{ Request::is('orders/attractions') ? 'active' : '' }}">
    <a href="{!! route('attraction_orders.index') !!}"><i class="fa fa-edit"></i><span>Attractions' Orders</span></a>
</li>

<li class="{{ Request::is('orders/attractions/approved*') ? 'active' : '' }}">
    <a href="{!! route('attraction_orders.approved_orders') !!}"><i class="fa fa-edit"></i><span>Approved Attraction's Orders</span></a>
</li>


<li class="{{ Request::is('venues*') ? 'active' : '' }}">
    <a href="{!! route('venues.index') !!}"><i class="fa fa-edit"></i><span>Venues</span></a>
</li>

<li class="{{ Request::is('notifications*') ? 'active' : '' }}">
    <a href="{!! route('notifications.index') !!}"><i class="fa fa-edit"></i><span>Notifications</span></a>
</li>

<li class="{{ Request::is('contact_us*') ? 'active' : '' }}">
    <a href="{!! route('contacts.index') !!}"><i class="fa fa-edit"></i><span>Contact Us</span></a>
</li>

<li class="{{ Request::is('about_uses*') ? 'active' : '' }}">
    <a href="{!! route('about_uses.index') !!}"><i class="fa fa-edit"></i><span>About Us</span></a>
</li>

<li class="{{ Request::is('newsletters*') ? 'active' : '' }}">
    <a href="{!! route('newsletters.index') !!}"><i class="fa fa-edit"></i><span>Newsletters</span></a>
</li>

<li class="{{ Request::is('contactuses*') ? 'active' : '' }}">
    <a href="{!! route('contactuses.index') !!}"><i class="fa fa-edit"></i><span>Contact Us Data</span></a>
</li>

