# jcaillot/owasp-listener

### OWASP header Response EventListener for the Symfony framework

> Symfony Response event listener, adds OWASP recommended HTTP headers

## Prerequisites

Symfony >= 4

## Installation

    `composer require jcaillot/owasp-listener`

In config/services.yaml add the following:

	  Chaman\EventListener\OwaspHeaderListener: 
	    tags:
	      - { name: kernel.event_listener, event: kernel.response, method: onKernelResponse }
        arguments:
            'Content-Type': 'text/html; charset=utf-8'
            'X-Content-Type-Option': 'nosniff' 
            'X-XSS-Protection': '1; mode=block'
            'X-Frame-Options': 'DENY' 
            'Content-Security-Policy': 'frame-ancestors \'none\'' 

## License

[MIT](https://choosealicense.com/licenses/mit/)

## More infos on OWASP recommended headers can be found:

[OWASP](https://wiki.owasp.org/index.php/OWASP_Secure_Headers_Project#tab=Headers)
