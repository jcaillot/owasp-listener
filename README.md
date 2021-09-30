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
           - {
             #'Strict-Transport-Security': 'max-age=31536000 ; includeSubDomains',
             'Content-Type': 'text/html; charset=utf-8',
             'X-Content-Type-Option': 'nosniff',
             'X-XSS-Protection': '1; mode=block',
             'X-Frame-Options': 'DENY',
             'X-Permitted-Cross-Domain-Policies': 'none',
             'Referrer-Policy': 'same-origin',
             'Content-Security-Policy': 'frame-ancestors ''none''',
             'Feature-Policy': 'camera: ''none''; payment: ''none''; microphone: ''none'''
            }

## About OWASP recommender headers

More infos on OWASP recommended headers can be found on the OWASP Secure Headers Project Wiki:

[OWASP](https://wiki.owasp.org/index.php/OWASP_Secure_Headers_Project#tab=Headers)

## License

[MIT](https://choosealicense.com/licenses/mit/)

