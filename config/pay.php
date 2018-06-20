<?php
return [
    'alipay' => [
        'app_id'         => '2016091300503332',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuw9FSO8hkBvh8wWzt55qQJ6AQzrBADdxwjv0Z+wPtktJM+viQ3S7RVankIesGE+0bm/DTHeNzo4hx+G08YvRWBh1kTdGv+NNHPMGGQzBr2DeaZvBVkD7RsM3BsigMurQLb9SdftHq0QgJkMM8oFSpx+b4sWitwAf3iMWElmQnNeI++Y9RahxrtlPM0sm/NlDf1F5isW+hrGOGFfWHrnPHRRKQrskGjGktudT/ldo4DHyGfDwxpD/DCUxqh/2Fr6PchjsdUbdNHmD7XZm9x61esR216ISLMb/96D3U4hUwGrbWal7dRWj3oraVJeXXYPOiuJNVBYG9x+45/w3RWXZ1QIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEAqASdlUTGq5pwIfsK/mZsfIp9ROgPxX2+Jdx9Y+b1IWESDSmSmH7D9EZxT0nbDeCaTgWCU2oMFz9CRH2bXwQ61LnIbBTDuCYkLLF/2z8wq/OrVBJHj6hmDJg34MYG2uXSKvqqDp3zYC3ahMqjHWXYutFELtknwnzuD+alm/ZvmPc0yLRXzCGlXEmVlmOPZnEsg8YuT2Nm7zKIQYor0mcRdARBjOrI5c9brCJnD5FPyG/5YzII/yvTY14QerwxGR/+4lJ2eNWE5HXlTIdFqMgVHEqzDQLlMvmR9MtOv0PJfvEHLGzdQLaC5Lk3QDOC3ntZQ5rliEOHW+huYdQU41eLRwIDAQABAoIBAQCNkXYFs7GCHdqlhxU4TazTMw1h5faD/PMei63LY2rf+R5woLq4avdI96G5oQ1FoB3j55hsGEqfu9lmRD+Jy9KWMwPAra5LlzebwONuJMwp1mJhsBVD4iw6KHfMmI5mGob/V726GzRZsckRYnwV5R68Kl886hQqN4GPPFQGGZrmUucLzF0r97j74XnwfZvpUpXpAFk1f7XOIKrGSbTD+fevLjToR7VupGGgY1qjmV411duiCY83R+oc/dE0G0ihheHzjJeJ8HKgi8odckzapeAunEcjCLFC3qa2b9BrXRX7xRenCcCW5oUin4p4l8CXOKlsufsiPiqbmFHOIM/t4PCBAoGBANfbPUtFq4s668MV7YfmVcMc+PMwmMhsrv3SBOD0yLIh0hl8MFHry1i0cv3oW+AUBcJqqnycpMwpja7Ea82rAeg9sIJjrckvwYIZxv/OM/mg0hbdtkg9YjRF1+4Ceqe5Oy5ZBrBZl1D6lzPlAeOWX4jtxEMERHDDHZoDFN+Z9TS3AoGBAMdD08OzjmPrNOMgbHoudvxPIQojEiWJ64NIUQCzTp28IQo8LJOdwDalZqFKbHwwRR6ye7YkXznwgoYWKmznA0a4l71r9bLpUkJCJAKqHENLRevVnwo0QHrd2vXyQnWIF8fSAHaX+uw4jWV40R0bheaspUo1QKfrBXpPHud7aW3xAoGAI3gqJUFIg/NlpEPpKSinNQ2Atu1oXZ7GTn0BQjnW9LZsALYQdpWpb2UPdDuHRVXk8GJ4q0tOJSI63tn14PDVumTQqUxZ1TJcuNUlhwlKLx3HB+zctBgqF+7WIY0UvJTKbb5BRB8kpzDWQbfCzLP79NMlKeH8oQ7ewVz6RBfXXBcCgYA055U5yFptvOz1wI4Mgnmk0316K4WN4ehdmr2m6fRdfK1pAkDzkFaQK6nr+M6EymwPkmO2IEIIKAr/frMU5uC/zhK7gAZj0f69CMJ20qNeWoXd74GiO+1CsdSIqCFtAw0NSHxHAGLmhL1Fy6X5jG0oqX3ZDfp0mrmBl7Ak1WdQgQKBgDJsORULLQWmI2yFnCvtEnmKTSKX5UtOl2FD1THLS0sT6F64afJ9P94pfveHd44R7XBFDGoQZxateLFTmgJD4A1HzlrEVH29QQpDWpDiK2AvAO7BwzfgYSwY+/MoLyymcymMufAwamnDldluxR1finjCvs/ZJXip1yAKRnZRSx00',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
