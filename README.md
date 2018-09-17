# NZord

Modelo para aplicação utilizando nzord. 
Inclue modulo: 
   - System , Obrigátorio para funcionamento.

##### Instalação 

Faça o clone do projeto

```bash

$ git clone https://github.com/coderlrv/nzord-skeleton.git

```

É recomendado que você use o [Composer](https://getcomposer.org/) para a instalação das dependencias.

```bash

$ composer install


# Start servidor local http:\\localhost:8080
$ composer server

```




Altere as configurações de conexões do banco de dados no arquivo `base/settings.php`

#####  Atenção

Definir a permissão de gravação para as pastas `base/tmp` e` files` ao implantar no ambiente de produção.


##### Diretórios
* `base`: Código de aplicação
* `files`: Diretório gravável dos arquivos jpg, png, pdf, bmp...
* `modulos`: Todos os Modulos utilizados pelo sistema
    - `system`: Módulo obrigátorio para funcionamento do nzord. Inclue Gerenciamento de Usuários,Logs,Relatórios,Setor,Organização, Paramentros entre outros..
* `public`: Raiz do webserver
* `vendor`: Composer dependencias


##### Twig
- Funções  Ex: `{{ dataExtenso(datas.data) }}`
    * `dataExtenso()` - Transforma data em data escrita.
    ```twig
        {{ dataExtenso('1969-12-31') }}
        //Result: 31 de dezembro de 1969
    ```
    * `valorPorExtenso()` - Transforma valor em valor escrito.
    ```twig
        {{ valorPorExtenso(52.00) }}
        //Result:  cinquenta e dois reais 
    ```
    * Gera link para modulo  `path_for_model()` -
    ```twig
        {{ path_for_model('meu-modulo','meu-controller','index', [12],['filtro'=>1]) }}  - Gera link
        //Result: http://localhost/nzord/app/meu-modulo/meu-controller/index/12&filtro=1
    ```
    *  Gera link Modal `path_for_modal()` - 
        ```twig
            {{ path_for_modal('meu-modulo','meu-controller','index', [12],['filtro'=>1]) }}  - Gera link para modal
           //Result: http://localhost/nzord/modal?p=app/meu-modulo/meu-controller/index/12&filtro=1
        ```

- Filtros Ex: `{{datas.valor | number_format}}`
    * `number_format` - Formata número para fomato pt-br.
    * `cpfCnpj` - Aplica mascará cpf ou cnpj.
    * `date('Y-m-d')` - Aplica data formato.


##### Modal
    


##### Test unitários e integração.
*  Diretorios para arquivos de testes.
    -   `base\tests\unit`: Pasta teste unitários
        - `meuTestTest.php`
    -   `base\tests\integration`: Pasta teste de integração

* Diretorios para modulos , segue mesmo modelo da base.
    - `modulos\nomemodulo\tests\unit`: Pasta teste unitários
       
    - `modulos\nomemodulo\integration`: Pasta teste de integração

* Executar testes
```bash
$ composer test
```