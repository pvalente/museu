applied preamble.txt (2665 chars)
Success: Applied inbcm-museologico.json
mode: single (first view, REST endpoint), model: site default, effort: default, max_tokens: site, museu_ai_image_max_edge: 1568
collection: Acervo Museológico (#127)

**Pass: 26/28** (Denominação and Classificação both right). Denominação 26/28, Classificação 27/28, Data de Produção 27/28 (informational).
Tokens: 177856 in + 28791 out over 28 requests (mean 6352 in, 1028 out).

| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |
|---|---|---|---|---|---|---|---|---|---|
| e1 | 1 | Escultura | Figura animal (escultura) | ✓ alt | Objetos associados às artes plásticas e ao desenho técnico | ✓ | — / — ✓ | — | 6486/681 |
| e2 | 1 | — | — | ✓ null | — | ✓ | 24-12-1914 / 24-12-1914 ✓ | — | 6262/2537 |
| e3 | 1 | Placa comemorativa | Placa comemorativa | ✓ exact | Objetos cerimoniais e/ou comemorativos | ✓ | 26/9/03 / 26/9/03 ✓ | — | 6206/774 |
| e4 | 1 | Bule | Bule | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 6318/1220 |
| e5 | 1 | Cesta | Cesta | ✓ exact | Recipientes | ✓ | — / — ✓ | — | 6150/660 |
| e6 | 1 | Ex-voto | Ex-voto | ✓ exact | Objetos rituais e cerimoniais | ✓ | 1766 / 1766 ✓ | — | 6486/1883 |
| e7 | 1 | Tigela | Tigela | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 6654/640 |
| e8 | 1 | — | — | ✓ null | — | ✓ | — / — ✓ | — | 6262/1270 |
| c01 | 1 | Azulejo | Azulejo | ✓ exact | Elementos de construção | ✓ | — / — ✓ | — | 6486/696 |
| c02 | 1 | Leque | Leque | ✓ exact | Objetos de auxílio, cuidados e conforto pessoais | ✓ | — / — ✓ | — | 6486/705 |
| c03 | 1 | Chapéu | Chapéu | ✓ exact | Vestuário | ✓ | — / — ✓ | — | 6262/678 |
| c04 | 1 | Plaina | Plaina | ✓ exact | Equipamento de atividades de transformação | ✓ | — / — ✓ | — | 6262/718 |
| c05 | 1 | Foice | Foice | ✓ exact | Equipamentos de agricultura, pecuária e pesca | ✓ | — / — ✓ | — | 6486/652 |
| c06 | 1 | Barômetro | Barômetro | ✓ exact | Equipamento associado à meteorologia | ✓ | — / — ✓ | — | 6262/1932 |
| c07 | 1 | Gramofone | Gramofone | ✓ exact | Equipamento de comunicação sonora | ✓ | — / — ✓ | — | 6262/1310 |
| c08 | 1 | Pião | Pião | ✓ exact | Equipamento lúdico | ✓ | — / — ✓ | — | 6262/1291 |
| c09 | 1 | Cédula | Cédula | ✓ exact | Dinheiro | ✓ | — / — ✓ | American Bank Note Co. New York | 5422/2482 |
| c10 | 1 | Estribo | Estribo | ✓ exact | Veículos terrestres e acessórios | ✓ | — / — ✓ | — | 6486/653 |
| c11 | 1 | Pistola | Pistola | ✓ exact | Armas e acessórios | ✓ | — / — ✓ | — | 6094/724 |
| c12 | 1 | Argola | Gargalheira | ✗ | Amostras e fragmentos | ✗ | — / — ✓ | — | 6262/1214 |
| c13 | 1 | Fragmento | Fragmento | ✓ exact | Amostras e fragmentos | ✓ | — / — ✓ | — | 6262/1196 |
| c14 | 1 | Violão | Violão | ✓ exact | Objetos associados à música | ✓ | — / — ✓ | — | 6486/672 |
| c15 | 1 | Castiçal | Palmatória (castiçal) | ✓ alt | Objetos e equipamentos de iluminação | ✓ | — / — ✓ | — | 6262/626 |
| c16 | 1 | Espelho de fechadura | Aldrava | ✗ | Elementos de construção | ✓ | — / — ✓ | — | 6486/629 |
| m1 | 1 | Sextante | Sextante | ✓ exact | Equipamentos associados à topografia e à navegação | ✓ | — / — ✓ | — | 6262/776 |
| m2 | 1 | Moeda | Moeda | ✓ exact | Dinheiro | ✓ | — / 1913 ✗ | — | 7270/829 |
| m3 | 1 | Carimbo | Carimbo | ✓ exact | Equipamento de comunicação escrita | ✓ | — / — ✓ | — | 6262/707 |
| m4 | 1 | Prato | Prato | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 6710/636 |

## e1: 1 view (claude-sonnet-5-5; in 6486, out 681, thinking 0, stop; system prompt 0 chars; images 1568x1176; 12 s)
**Denominação** ✓ (expected "Figura animal (escultura)", or Escultura) — **Classificação** ✓ 

- inbcm-denominacao: "Escultura"
  evidence: "Tesauro (exact: \"Escultura\"): Objetos de atividades artísticas › Objetos associados às artes plásticas e ao desenho técnico › Escultura"
- titulo-2: "Escultura de tucano em madeira com bico grande"
  evidence: "Figura de ave com bico longo e curvo, em madeira, com olho em círculos concêntricos pintado. A identificação como tucano se baseia na forma do bico."
- inbcm-autor: null
- descricao-2: "Escultura de ave em madeira aparentemente entalhada, com bico grande e curvo, de tom claro com uma faixa marrom-escura. A cabeça tem olho pintado em círculos concêntricos pretos, e o corpo apresenta acabamento em tom dourado-escuro. As patas são lâminas planas com listras pretas. Há pequenas marcas e desgaste leve na superfície."
  evidence: "Observação direta do objeto: bico claro com faixa escura, olho em círculos concêntricos, corpo com acabamento dourado-escuro, patas listradas."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Entalhe","Pintura"]
  evidence: ["Superfície com veios e aspecto de madeira clara no bico e na cabeça.","Formas esculpidas com facetas e cortes visíveis no bico, na cabeça e nas patas.","Olho em círculos concêntricos pretos, listras nas patas e acabamento dourado-escuro no corpo."]
- inbcm-conservacao: "Bom"
  evidence: "Sem fraturas ou perdas visíveis; apenas desgaste leve e pequenas marcas na superfície da madeira."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos associados às artes plásticas e ao desenho técnico"
  evidence: "Tesauro: classe de Escultura"
  tesauro: names=["Escultura","Escultura de tucano em madeira com bico grande"] -> ["Escultura (exact 1)"]

## e2: 1 view (claude-sonnet-5-5; in 6262, out 2537, thinking 1545, stop; system prompt 0 chars; images 1568x1042; 24 s)
**Denominação** ✓ (expected null) — **Classificação** ✓ 

- inbcm-denominacao: null
  new terms: ["Cartão"]
- titulo-2: "Cartão manuscrito de boas festas"
  evidence: "Texto manuscrito: 'desejando bôas festas e innumeras \/ prosperidades no decorrer do anno \/ novo'."
- inbcm-autor: null
- descricao-2: "Cartão retangular de papel marrom, com cantos superiores e inferiores irregulares e manchas escuras espalhadas. Apresenta um vinco ou rasgo horizontal no alto, ao centro, e pequenos furos perto do início da primeira linha. A mensagem foi escrita à mão, em tinta preta e letra cursiva, sobre texto impresso desbotado. O papel tem desgaste nas bordas.\n\nInscrições: manuscrito, ao centro e na metade superior: \"Ao bondoso amigo Lula \/ o[?] \/ Carlito abraça \/ desejando bôas festas e innumeras \/ prosperidades no decorrer do anno \/ novo\"; impresso desbotado, ao centro, sob a escrita manuscrita: \"Car[ilegível] [ilegível] Avancini[?]\"; impresso desbotado, canto inferior esquerdo: \"Villa de Santa Thereza\"; manuscrito, parte inferior: \"24-12-1914\"; impresso desbotado, canto inferior direito: \"E. E. Santo[?]\"."
  evidence: "Papel marrom manchado com vinco superior e cantos irregulares; escrita manuscrita em tinta preta; textos impressos desbotados ao centro e nos cantos inferiores."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Escrita manual","Impressão"]
  evidence: ["Suporte de papel marrom, com manchas e bordas irregulares.","Mensagem e data escritas à mão em tinta preta, em letra cursiva.","Texto impresso desbotado ao centro e nos cantos inferiores ('Villa de Santa Thereza', 'E. E. Santo')."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras generalizadas, vinco ou rasgo horizontal no alto, cantos irregulares e pequenos furos; texto ainda legível."
- inbcm-localprod: null
- inbcm-dataprod: "24-12-1914"
  evidence: "Data manuscrita na parte inferior, ao lado do texto impresso 'Villa de Santa Thereza': '24-12-1914'."
  tesauro: names=["Cartão","Cartão manuscrito de boas festas"] -> []

## e3: 1 view (claude-sonnet-5-5; in 6206, out 774, thinking 0, stop; system prompt 0 chars; images 1568x1027; 9 s)
**Denominação** ✓ (expected "Placa comemorativa") — **Classificação** ✓ 

- inbcm-denominacao: "Placa comemorativa"
  evidence: "Tesauro (exact: \"Placa comemorativa\"): Insígnias e objetos cerimoniais e comemorativos › Objetos cerimoniais e\/ou comemorativos › Placa comemorativa"
- titulo-2: "Placa gravada de saudação a Santos Dumont"
  evidence: "Texto gravado ao centro: \"Ao glorioso aeronauta \/ Dr. Santos Dumont \/ O sport nautico da Bahia saúda a vossa passagem por esta Capital\""
- inbcm-autor: null
- descricao-2: "Placa retangular de metal dourado, aparentemente liso e polido, com texto gravado em letra cursiva. Uma moldura de arabescos e laços gravados contorna as bordas, com motivos mais elaborados nos cantos e no centro da borda superior. O texto ocupa o campo central, com data ao final, no canto inferior direito. A superfície apresenta riscos finos e leves manchas.\n\nInscrições: gravado, ao centro: \"Ao glorioso aeronauta \/ Dr. Santos Dumont \/ O sport nautico da \/ Bahia saúda a vossa \/ passagem por esta \/ Capital.\"; gravado, canto inferior direito: \"26\/9\/03\""
  evidence: "Texto cursivo gravado em toda a placa; ornamentos gravados nas bordas; superfície metálica dourada com riscos finos."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Gravação"]
  evidence: ["Superfície metálica dourada, aparentemente de liga de cobre ou latão.","Texto cursivo e ornamentos em linhas incisas, aparentemente gravados."]
- inbcm-conservacao: "Bom"
  evidence: "Gravação nítida e legível; apenas riscos finos e leves manchas na superfície, sem perdas ou deformações."
- inbcm-localprod: null
- inbcm-dataprod: "26\/9\/03"
  evidence: "Data gravada no canto inferior direito: \"26\/9\/03\"."
- inbcm-classificacao: "Objetos cerimoniais e\/ou comemorativos"
  evidence: "Tesauro: classe de Placa comemorativa"
  tesauro: names=["Placa comemorativa","Placa gravada de saudação a Santos Dumont"] -> ["Placa comemorativa (exact 1)"]

## e4: 1 view (claude-sonnet-5-5; in 6318, out 1220, thinking 604, stop; system prompt 0 chars; images 1568x1067; 12.3 s)
**Denominação** ✓ (expected "Bule", or Bule de chá, Bule de café) — **Classificação** ✓ 

- inbcm-denominacao: "Bule"
  evidence: "Tesauro (exact: \"Bule\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Bule. Alternativas: Bule (samovar) — Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Bule › Bule (samovar)"
- titulo-2: "Bule de metal prateado com monograma gravado"
  evidence: "Corpo metálico escurecido com cartela central gravada contendo monograma entrelaçado."
- inbcm-autor: null
- descricao-2: "Bule de corpo bojudo e base circular, em metal aparentemente prateado, com superfície escurecida e texturizada. Tem bico reto e tubular, tampa com botão decorativo e alça curva ornamentada com anéis claros, de material possivelmente osso ou marfim. Apresenta oxidação, manchas na tampa e desgaste na base.\n\nInscrições: gravado, em cartela ao centro do corpo: monograma de letras entrelaçadas [ilegível]"
  evidence: "Cartela gravada no centro do corpo com monograma entrelaçado; tampa com manchas; anéis claros na alça; corpo com textura martelada e escurecido."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Gravação"]
  evidence: ["Corpo, tampa, bico e alça de metal escurecido, aparentemente prateado.","Cartela central com decoração e monograma aparentemente gravados."]
- inbcm-conservacao: "Regular"
  evidence: "Escurecimento, manchas na tampa e desgaste visível na superfície, sem perdas ou fraturas aparentes."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Bule"
  tesauro: names=["Bule","Bule de metal prateado com monograma gravado"] -> ["Bule (exact 1)","Bule (samovar) (exact 0.98)"]

## e5: 1 view (claude-sonnet-5-5; in 6150, out 660, thinking 0, stop; system prompt 0 chars; images 1568x985; 7.6 s)
**Denominação** ✓ (expected "Cesta") — **Classificação** ✓ 

- inbcm-denominacao: "Cesta"
  evidence: "Tesauro (variant: \"cesta\"): Recipientes › Cesta"
- titulo-2: "Cesto oval com tampa de tiras vegetais amarradas"
  evidence: "Forma oval baixa com tampa, laterais de tiras verticais, amarrações em fibra e fio solto à esquerda."
- inbcm-autor: null
- descricao-2: "Cesto oval baixo, com tampa, feito de tiras vegetais estreitas e claras dispostas na vertical, aparentemente unidas por amarrações de fibra. A tampa tem tiras em disposição radial, com costura em fibra trançada ao longo da borda e varetas finas nas bordas. Um fio de fibra solto pende do lado esquerdo. A cor é bege claro, com manchas marrons e tiras levemente soltas nas laterais."
  evidence: "Corpo oval com tiras verticais, tampa com tiras radiais, aro de varetas, fio solto, manchas e tiras soltas visíveis."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Fibra vegetal","Trançado"]
  evidence: ["Tiras e fios de aparência vegetal compõem o corpo, a tampa e as amarrações.","Tiras entrelaçadas e amarradas formam as paredes e a tampa do cesto."]
- inbcm-conservacao: "Regular"
  evidence: "Fio solto, tiras levemente deslocadas nas laterais e manchas marrons, sem perdas que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Recipientes"
  evidence: "Tesauro: classe de Cesta"
  tesauro: names=["Cesto","Cesto oval com tampa de tiras vegetais amarradas"] -> ["Cesta (variant 0.75)"]

## e6: 1 view (claude-sonnet-5-5; in 6486, out 1883, thinking 1018, stop; system prompt 0 chars; images 1568x1157; 16.8 s)
**Denominação** ✓ (expected "Ex-voto") — **Classificação** ✓ 

- inbcm-denominacao: "Ex-voto"
  evidence: "Tesauro (exact: \"Ex-voto\"): Objetos de ritos, cultos e crenças › Objetos rituais e cerimoniais › Ex-voto"
- titulo-2: "Ex-voto pintado com legenda manuscrita"
  evidence: "Cena pintada e legenda inferior manuscrita com agradecimento (\"Graças a DEOS para Sempre\")"
- inbcm-autor: null
- descricao-2: "Pintura aparentemente sobre suporte de madeira, em moldura de madeira vermelha com craquelê. A cena mostra uma mulher de hábito escuro e branco deitada em leito, cercada por religiosas e homens de pé à esquerda, com uma figura ajoelhada em primeiro plano. À direita, figuras com auréola aparecem entre nuvens. Há uma fenda horizontal no suporte e craquelê na moldura.\n\nInscrições: manuscrito, faixa inferior: \"Merce[?] q fez N. S. do Desti.[?] de soror D. Anna Jozefa dos Anjos, Estando gravem.te enferma \/ dehũa malina[?] da qual Sevio Livre, Não se[?] julgando mais Vida, p.r entercessão Da Mes- \/ ma Senhr.a Graças a DEOS para Sempre Anno de 1766\""
  evidence: "Cena central com leito, religiosas e figuras em nuvens; fenda horizontal no suporte; faixa de texto manuscrito na parte inferior"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Pintura","Escrita manual"]
  evidence: ["Fenda horizontal no suporte e moldura vermelha de madeira aparente","Cena figurativa pintada, aparentemente a óleo","Legenda manuscrita na faixa inferior com iniciais em vermelho"]
- inbcm-conservacao: "Regular"
  evidence: "Fenda horizontal atravessando a pintura, tinta da moldura craquelada e desgastada, cores desbotadas, mas cena e texto legíveis"
- inbcm-localprod: null
- inbcm-dataprod: "1766"
  evidence: "Legenda manuscrita inferior: \"Anno de 1766\""
- inbcm-classificacao: "Objetos rituais e cerimoniais"
  evidence: "Tesauro: classe de Ex-voto"
  tesauro: names=["Ex-voto","Ex-voto pintado com legenda manuscrita"] -> ["Ex-voto (exact 1)"]

## e7: 1 view (claude-sonnet-5-5; in 6654, out 640, thinking 0, stop; system prompt 0 chars; images 1568x1237; 8.4 s)
**Denominação** ✓ (expected "Tigela", or Vasilha) — **Classificação** ✓ 

- inbcm-denominacao: "Tigela"
  evidence: "Tesauro (exact: \"Tigela\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Tigela"
- titulo-2: "Tigela de cerâmica com pintura geométrica em preto e vermelho"
  evidence: "Corpo aparentemente cerâmico, com pintura geométrica em linhas pretas e vermelhas sobre fundo claro, visível por dentro e por fora."
- inbcm-autor: null
- descricao-2: "Tigela de boca ampla e borda levemente voltada para fora, aparentemente de cerâmica com pintura. A face interna tem motivos geométricos de linhas pretas grossas, com preenchimento de pequenos traços pretos e contornos vermelhos finos sobre fundo claro. A face externa tem faixas curvas concêntricas em vermelho e áreas em preto e bege. A borda apresenta lascas e perdas de pintura."
  evidence: "Forma, material aparente e decoração observados no objeto; lascas visíveis na borda."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Cerâmica","Pintura"]
  evidence: ["Corpo aparentemente de cerâmica, com superfície de aspecto argiloso e queimado.","Motivos geométricos pintados em preto e vermelho sobre fundo claro, nas faces interna e externa."]
- inbcm-conservacao: "Regular"
  evidence: "Lascas e perdas de pintura na borda, com depósitos esbranquiçados; a decoração segue legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Tigela"
  tesauro: names=["Tigela","Tigela de cerâmica com pintura geométrica em preto e vermelho"] -> ["Tigela (exact 1)"]

## e8: 1 view (claude-sonnet-5-5; in 6262, out 1270, thinking 574, stop; system prompt 0 chars; images 1568x1043; 13.1 s)
**Denominação** ✓ (expected null) — **Classificação** ✓ 

- inbcm-denominacao: null
  new terms: ["Cartão"]
- titulo-2: "Cartão de papel marrom com escrita manuscrita espelhada"
  evidence: "Suporte retangular marrom com linhas de escrita manuscrita em letras invertidas."
- inbcm-autor: null
- descricao-2: "Cartão retangular de papel ou cartão, de cor marrom com manchas escuras irregulares. Apresenta cantos desgastados, vincos e uma pequena rasgadura horizontal na parte superior central. Há escrita manual em tinta escura, em letra cursiva, vista de forma espelhada, o que indica que transparece do outro lado do suporte.\n\nInscrições: manuscrito, em várias linhas na área central e abaixo, com possível data na parte inferior; o texto está espelhado (transparecendo do outro lado) e não é transcrito."
  evidence: "Papel marrom manchado; rasgo horizontal acima do centro; cantos danificados; letras cursivas invertidas em quatro linhas e uma linha final menor."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Escrita manual"]
  evidence: ["Suporte fibroso, fino e marrom, com vincos e rasgadura.","Texto cursivo em tinta escura, aparentemente escrito à mão."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras generalizadas, vincos, pequena rasgadura horizontal no alto e cantos dobrados ou com perdas leves, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
  tesauro: names=["Cartão","Cartão de papel marrom com escrita manuscrita espelhada"] -> []

## c01: 1 view (claude-sonnet-5-5; in 6486, out 696, thinking 0, stop; system prompt 0 chars; images 1176x1568; 8.2 s)
**Denominação** ✓ (expected "Azulejo") — **Classificação** ✓ 

- inbcm-denominacao: "Azulejo"
  evidence: "Tesauro (exact: \"Azulejo\"): Estruturas edificadas e elementos de construção › Elementos de construção › Azulejo"
- titulo-2: "Azulejo com flor azul e amarela em reserva central"
  evidence: "Motivo floral azul com centro amarelo em reserva branca de contorno sinuoso, cantos em azul claro"
- inbcm-autor: null
- descricao-2: "Azulejo quadrado de cerâmica aparentemente vidrada, com decoração pintada à mão. Ao centro, uma flor estilizada de pétalas azuis e miolo amarelo, cercada por pequenos traços azuis, sobre reserva branca de contorno sinuoso delineado em azul escuro. Os cantos são pintados em tons de azul claro. Apresenta uma trinca diagonal no esmalte, manchas amareladas e marrons, pequenas lascas e perdas de esmalte nas bordas."
  evidence: "Peça quadrada com flor central azul e amarela; linha diagonal visível na reserva branca; manchas e lascas nas bordas e cantos"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Cerâmica","Pintura"]
  evidence: ["Corpo quadrado de aparência cerâmica com superfície vidrada brilhante, lascas expondo as bordas","Motivo floral e cantos aparentemente pintados à mão em azul e amarelo, com pinceladas visíveis"]
- inbcm-conservacao: "Regular"
  evidence: "Trinca diagonal no esmalte da reserva branca, manchas amareladas\/marrons e pequenas lascas nas bordas, sem comprometer a leitura do motivo"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Elementos de construção"
  evidence: "Tesauro: classe de Azulejo"
  tesauro: names=["Azulejo","Azulejo com flor azul e amarela em reserva central"] -> ["Azulejo (exact 1)"]

## c02: 1 view (claude-sonnet-5-5; in 6486, out 705, thinking 0, stop; system prompt 0 chars; images 1568x1176; 9.1 s)
**Denominação** ✓ (expected "Leque") — **Classificação** ✓ 

- inbcm-denominacao: "Leque"
  evidence: "Tesauro (exact: \"Leque\"): Objetos de uso pessoal › Objetos de auxílio, cuidados e conforto pessoais › Leque"
- titulo-2: "Leque dobrável com pintura de flores e borboletas"
  evidence: "Folha plissada pintada com margaridas, flores azuis, uma flor vermelha e duas borboletas; varetas perfuradas."
- inbcm-autor: null
- descricao-2: "Leque dobrável com folha plissada de tecido ou papel de tom bege-dourado, pintada com margaridas brancas, flores azuis, uma flor vermelha e borboletas azul-esverdeadas. As varetas, de material escuro aparentemente de madeira, têm a parte inferior vazada com motivos ornamentais. As hastes externas têm decoração aparentemente incisa ou dourada. Uma haste está solta, fora do leque. Há manchas, escurecimento e desgaste na folha e nas hastes."
  evidence: "Folha pintada à mão com flores e borboletas; varetas vazadas na base; haste solta na parte inferior; manchas e escurecimento visíveis."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Pintura","Tecido"]
  evidence: ["Varetas e hastes escuras aparentemente de madeira, com parte inferior vazada.","Motivos florais e borboletas aparentemente pintados à mão sobre a folha.","Folha plissada aparentemente de tecido; o material não é totalmente claro."]
- inbcm-conservacao: "Regular"
  evidence: "Folha com manchas e escurecimento, bordas desgastadas e haste externa solta; objeto ainda legível e íntegro em sua maior parte."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos de auxílio, cuidados e conforto pessoais"
  evidence: "Tesauro: classe de Leque"
  tesauro: names=["Leque","Leque dobrável com pintura de flores e borboletas"] -> ["Leque (exact 1)"]

## c03: 1 view (claude-sonnet-5-5; in 6262, out 678, thinking 0, stop; system prompt 0 chars; images 1568x1044; 8.5 s)
**Denominação** ✓ (expected "Chapéu", or Chapéu-panamá) — **Classificação** ✓ 

- inbcm-denominacao: "Chapéu"
  evidence: "Tesauro (exact: \"Chapéu\"): Objetos de uso pessoal › Vestuário › Cobertura de cabeça › Chapéu"
- titulo-2: "Chapéu de palha trançada com fita preta"
  evidence: "Trama aparentemente de fibra vegetal trançada na cor bege; faixa preta de tecido ao redor da copa."
- inbcm-autor: null
- descricao-2: "Chapéu de copa alta, com leve amassado no topo, e aba larga e levemente caída. É feito em trama fina aparentemente de fibra vegetal trançada, na cor bege. Uma faixa larga de tecido preto contorna a base da copa. A faixa apresenta rasgos, perdas e fios soltos na parte frontal, e a trama exibe manchas leves e pequenos furos junto à fita."
  evidence: "Copa amassada no topo; fita preta com rasgos e fios soltos na lateral esquerda; pequenos furos junto à base da copa. Nenhum texto visível."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Fibra vegetal","Trançado","Tecido"]
  evidence: ["Corpo do chapéu com trama fina de aspecto vegetal, de cor bege.","Trama entrelaçada visível na copa e na aba, com espiral concêntrica no topo.","Faixa preta de tecido com textura de gorgorão ao redor da copa."]
- inbcm-conservacao: "Regular"
  evidence: "Fita preta com rasgos, perdas e fios soltos; copa deformada e pequenos furos junto à base da copa; trama íntegra e leitura do objeto preservada."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Vestuário"
  evidence: "Tesauro: classe de Chapéu"
  tesauro: names=["Chapéu","Chapéu de palha trançada com fita preta"] -> ["Chapéu (exact 1)"]

## c04: 1 view (claude-sonnet-5-5; in 6262, out 718, thinking 0, stop; system prompt 0 chars; images 1568x1046; 8.4 s)
**Denominação** ✓ (expected "Plaina", or Plaina manual) — **Classificação** ✓ 

- inbcm-denominacao: "Plaina"
  evidence: "Tesauro (exact: \"Plaina\"): Equipamento de atividades produtivas › Equipamento de atividades de transformação › Plaina"
- titulo-2: "Plaina de madeira com cunha e lâmina metálica"
  evidence: "Corpo alongado de madeira com abertura central, cunha de madeira com pontilhado incisado e lâmina metálica visível na abertura"
- inbcm-autor: null
- descricao-2: "Plaina manual de corpo alongado e retangular, de madeira aparentemente entalhada, em tom marrom-claro. Na abertura central há uma cunha de madeira com fileira de pontos incisos e uma lâmina de metal. Uma peça cilíndrica de madeira projeta-se na extremidade direita. A superfície apresenta manchas escuras, desgaste, pequenas rachaduras e uma fenda longitudinal na base."
  evidence: "Corpo de madeira com manchas escuras à esquerda, rachadura no topo da peça cilíndrica à direita, fenda ao longo da parte inferior, pontilhado ao redor da abertura e na cunha"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Entalhe","Metal"]
  evidence: ["Corpo, cunha e peça cilíndrica com veios de madeira visíveis","Madeira aparentemente entalhada, com abertura recortada e pontilhado incisado na cunha e ao redor da abertura","Lâmina de aspecto metálico visível na abertura central"]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras na face esquerda, rachaduras na peça cilíndrica e fenda na base, sem perdas que comprometam a leitura do objeto"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de atividades de transformação"
  evidence: "Tesauro: classe de Plaina"
  tesauro: names=["Plaina","Plaina de madeira com cunha e lâmina metálica"] -> ["Plaina (exact 1)"]

## c05: 1 view (claude-sonnet-5-5; in 6486, out 652, thinking 0, stop; system prompt 0 chars; images 1176x1568; 7.8 s)
**Denominação** ✓ (expected "Foice") — **Classificação** ✓ 

- inbcm-denominacao: "Foice"
  evidence: "Tesauro (exact: \"Foice\"): Equipamento de atividades produtivas › Equipamentos de agricultura, pecuária e pesca › Foice"
- titulo-2: "Foice de lâmina curva de metal com cabo de madeira"
  evidence: "Lâmina curva em metal oxidado e cabo cilíndrico de madeira."
- inbcm-autor: null
- descricao-2: "Foice com lâmina de metal em forma de meia-lua, de ponta afilada, com oxidação e manchas claras em toda a superfície. Há pequenas perdas furadas na base da lâmina, próximas ao cabo. O cabo é cilíndrico, de madeira aparentemente torneada, com manchas escuras e desgaste. A lâmina é fixada ao cabo por uma haste metálica.\n\nInscrições: gravado ou estampado, na base da lâmina, junto ao cabo: [ilegível]"
  evidence: "Lâmina curva com oxidação e pequenos furos na região inferior; cabo de madeira cilíndrico; marca pequena e ilegível na base da lâmina, perto do cabo."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira"]
  evidence: ["Lâmina curva de metal com oxidação visível.","Cabo cilíndrico de madeira, de cor clara e com manchas."]
- inbcm-conservacao: "Regular"
  evidence: "Oxidação generalizada na lâmina, pequenos furos na base da lâmina e manchas no cabo, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos de agricultura, pecuária e pesca"
  evidence: "Tesauro: classe de Foice"
  tesauro: names=["Foice","Foice de lâmina curva de metal com cabo de madeira"] -> ["Foice (exact 1)"]

## c06: 1 view (claude-sonnet-5-5; in 6262, out 1932, thinking 1043, stop; system prompt 0 chars; images 1568x1046; 18 s)
**Denominação** ✓ (expected "Barômetro", or Barômetro aneróide) — **Classificação** ✓ 

- inbcm-denominacao: "Barômetro"
  evidence: "Tesauro (exact: \"Barômetro\"): Equipamentos de atividades científicas e tecnológicas › Equipamento associado à meteorologia › Barômetro"
- titulo-2: "Barômetro circular com moldura de madeira preta"
  evidence: "Corpo circular em camadas de madeira enegrecida, com argola metálica no topo e mostrador prateado."
- inbcm-autor: null
- descricao-2: "Barômetro circular de parede, com moldura em camadas de madeira aparentemente torneada e escurecida, com friso de dentes entalhados. Mostrador prateado sob vidro, com escalas numéricas, duas agulhas e mecanismo metálico aparente ao centro. Aro metálico dourado em torno do mostrador e argola de suspensão no topo. A moldura apresenta lascas e perdas de acabamento na borda.\n\nInscrições: impresso, no mostrador, em arco ao longo da borda: \"TEMPESTAD\" \/ \"Gª LLUVIA.[?]\" \/ \"LLUVIA Y VIENTO\" \/ \"VARIABLE.\" \/ \"BUEN TIEMPO.\" \/ \"B. TIEMPO FIJO.\" \/ \"MUY SECO.\"; o mostrador também traz escalas numéricas, não transcritas."
  evidence: "Texto impresso no mostrador; moldura escura com friso serrilhado; argola metálica superior; lascas visíveis na borda esquerda e à direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Metal","Vidro","Entalhe","Impressão"]
  evidence: ["Moldura escura aparentemente de madeira","Aro do mostrador e argola de suspensão metálicos","Cobertura transparente sobre o mostrador","Friso de dentes entalhados na moldura","Legendas e escalas impressas no mostrador"]
- inbcm-conservacao: "Regular"
  evidence: "Lascas e perda de acabamento na borda da moldura (lado esquerdo e direito); mostrador e vidro com manchas e desgaste, mas legíveis."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento associado à meteorologia"
  evidence: "Tesauro: classe de Barômetro"
  tesauro: names=["Barômetro","Barômetro circular com moldura de madeira preta"] -> ["Barômetro (exact 1)"]

## c07: 1 view (claude-sonnet-5-5; in 6262, out 1310, thinking 597, stop; system prompt 0 chars; images 1568x1046; 12.6 s)
**Denominação** ✓ (expected "Gramofone") — **Classificação** ✓ 

- inbcm-denominacao: "Gramofone"
  evidence: "Tesauro (exact: \"Gramofone\"): Equipamento de comunicação › Equipamento de comunicação sonora › Fonógrafo › Gramofone"
- titulo-2: "Gramofone com corneta de metal pintada de vermelho"
  evidence: "Corneta grande pintada de vermelho com faixas em tom dourado e cinza, sobre caixa de madeira escura."
- inbcm-autor: null
- descricao-2: "Gramofone com caixa de madeira escura de base quadrada, com molduras e pé escalonado. Sobre a caixa há um prato giratório circular de metal e um braço curvo de metal, de onde parte uma grande corneta em forma de sino alongado. A corneta, aparentemente de metal, é pintada de vermelho com faixas em tom dourado e cinza e tem a borda com ornamentos em relevo. A pintura apresenta desgaste, com perdas e manchas, e a madeira mostra marcas de uso."
  evidence: "Caixa de madeira escura com base moldurada; prato e braço metálicos; corneta vermelha com faixas douradas e cinza; tinta desgastada e arranhões na madeira da base. Nenhuma inscrição legível no objeto."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Metal","Pintura"]
  evidence: ["Caixa e base de madeira escura com acabamento envernizado.","Corneta, braço e prato de aparência metálica.","Corneta com pintura vermelha e faixas douradas e cinza, com desgaste visível."]
- inbcm-conservacao: "Regular"
  evidence: "Pintura da corneta desgastada, com perdas e manchas, e marcas de uso na madeira da base. O objeto está íntegro e legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação sonora"
  evidence: "Tesauro: classe de Gramofone"
  tesauro: names=["Gramofone","Gramofone com corneta de metal pintada de vermelho"] -> ["Gramofone (exact 1)"]

## c08: 1 view (claude-sonnet-5-5; in 6262, out 1291, thinking 570, stop; system prompt 0 chars; images 1568x1044; 13.5 s)
**Denominação** ✓ (expected "Pião") — **Classificação** ✓ 

- inbcm-denominacao: "Pião"
  evidence: "Tesauro (exact: \"Pião\"): Equipamentos de lazer e esporte › Equipamento lúdico › Pião"
- titulo-2: "Pião de metal com cabo de madeira e listras coloridas"
  evidence: "Corpo metálico com listras rosa e azul-acinzentadas sobre fundo creme; cabo superior de madeira marrom-avermelhada."
- inbcm-autor: null
- descricao-2: "Pião com corpo em forma de dois cones sobrepostos, aparentemente de folha de metal com decoração impressa. O fundo é creme, com listras rosa e azul-acinzentadas, e a faixa superior é verde, com figuras ilustradas. A borda tem furos ovais e a ponta inferior é metálica, magenta e perfurada. O cabo superior é de madeira marrom-avermelhada, com haste metálica espiralada. Há desgaste da pintura e pequenas marcas na superfície."
  evidence: "Corpo metálico com listras, faixa verde com figuras, furos ovais na borda, ponta magenta perfurada, cabo de madeira com haste espiralada. Nenhum texto inscrito legível no objeto."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira"]
  evidence: ["Corpo de folha metálica com brilho prateado nas bordas e na ponta magenta.","Cabo superior de madeira torneada, marrom-avermelhada."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste visível da pintura nas listras e na borda, com arranhões e pequenas marcas no metal. A forma e a decoração continuam legíveis."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento lúdico"
  evidence: "Tesauro: classe de Pião"
  tesauro: names=["Pião","Pião de metal com cabo de madeira e listras coloridas"] -> ["Pião (exact 1)"]

## c09: 1 view (claude-sonnet-5-5; in 5422, out 2482, thinking 1394, stop; system prompt 0 chars; images 1568x643; 20.3 s)
**Denominação** ✓ (expected "Cédula") — **Classificação** ✓ 

- inbcm-denominacao: "Cédula"
  evidence: "Tesauro (exact: \"Cédula\"): Objetos pecuniários › Dinheiro › Cédula"
- titulo-2: "Cédula impressa de um mil réis com retrato e brasão"
  evidence: "Texto impresso \"UM MIL REIS\"; retrato oval ao centro e brasão coroado à esquerda."
- inbcm-autor: ["American Bank Note Co. New York"]
  evidence: ["Impresso na borda inferior central: \"American Bank Note Co. New York\"."]
- descricao-2: "Cédula retangular em papel, aparentemente impressa em preto, com detalhes em verde-claro e numeração em vermelho. Ao centro há um retrato oval de homem de barba; à esquerda, um brasão encimado por coroa, e à direita, uma figura feminina sentada. Há uma assinatura manuscrita a tinta na parte inferior. O papel apresenta vincos, manchas, bordas desgastadas e uma pequena falha na borda superior.\n\nInscrições: impresso, faixa superior: \"IMPERIO DO BRASIL \/ NO THESOURO NACIONAL\"; impresso, abaixo: \"SERIE\" e \"ESTAMPA\", com \"53ª\" e \"6ª\"; impresso em vermelho, canto superior direito: \"2413\"; impresso, ao centro: \"Se pagará \/ desta a \/ UM MIL\" e \"ao portador \/ quantia de \/ REIS\"; impresso, faixa vertical à esquerda: \"UM MIL REIS\"; impresso em vermelho, caixa inferior esquerda: \"No. 2413\"; impresso, parte inferior central: \"VALOR \/ RECEBIDO\"; impresso, borda inferior: \"American Bank Note Co. New York\"; impresso, canto superior direito: \"A\"; manuscrito a tinta, parte inferior: [ilegível]."
  evidence: "Texto impresso na faixa superior, no centro, na borda inferior e nos números vermelhos; assinatura manuscrita ilegível na parte inferior; vincos, manchas e falha na borda superior."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Impressão","Escrita manual"]
  evidence: ["Suporte aparentemente de papel, com envelhecimento e vincos.","Texto, ornamentos e gravuras impressos em preto, verde e vermelho.","Assinatura manuscrita a tinta na parte inferior."]
- inbcm-conservacao: "Regular"
  evidence: "Vincos, manchas, bordas desgastadas e pequena falha na borda superior, sem comprometer a leitura."
- inbcm-localprod: "New York"
  evidence: "Impresso na borda inferior: \"American Bank Note Co. New York\"."
- inbcm-dataprod: null
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Cédula"
  tesauro: names=["Cédula","Cédula impressa de um mil réis com retrato e brasão"] -> ["Cédula (exact 1)"]

## c10: 1 view (claude-sonnet-5-5; in 6486, out 653, thinking 0, stop; system prompt 0 chars; images 1176x1568; 8.9 s)
**Denominação** ✓ (expected "Estribo") — **Classificação** ✓ 

- inbcm-denominacao: "Estribo"
  evidence: "Tesauro (exact: \"Estribo\"): Veículos e acessórios › Veículos terrestres e acessórios › Arreio › Estribo"
- titulo-2: "Estribo de metal com base vazada e decoração em relevo"
  evidence: "Arco metálico com base de pisada perfurada e laterais com ornamentos em relevo, incluindo estrela central."
- inbcm-autor: null
- descricao-2: "Estribo de metal de cor dourada a acobreada, aparentemente fundido, com arco superior alto e haste cilíndrica torcida no topo. A base tem plataforma de pisada com aberturas em forma de raios e abas laterais curvas. A face frontal apresenta ornamentos em relevo, com estrela central circular, folhagens e figuras aladas, além de trechos vazados e borda perolada. A superfície tem desgaste e escurecimento, com pontos prateados na haste."
  evidence: "Corpo metálico com acabamento dourado\/acobreado, plataforma vazada, relevo decorativo com estrela ao centro e manchas de oxidação visíveis."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Fundição"]
  evidence: ["Superfície metálica de cor dourada a acobreada em todo o objeto.","Ornamentos em relevo e peças vazadas, aparentemente obtidos por fundição."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste, escurecimento e manchas na superfície metálica; peça aparentemente completa e legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Veículos terrestres e acessórios"
  evidence: "Tesauro: classe de Estribo"
  tesauro: names=["Estribo","Estribo de metal com base vazada e decoração em relevo"] -> ["Estribo (exact 1)"]

## c11: 1 view (claude-sonnet-5-5; in 6094, out 724, thinking 0, stop; system prompt 0 chars; images 1568x963; 8.9 s)
**Denominação** ✓ (expected "Pistola") — **Classificação** ✓ 

- inbcm-denominacao: "Pistola"
  evidence: "Tesauro (exact: \"Pistola\"): Equipamentos de caça e de guerra › Armas e acessórios › Arma de fogo › Pistola"
- titulo-2: "Pistola de percussão com coronha de madeira entalhada"
  evidence: "Cão externo sobre a caixa da platina, cano escuro e coronha com decoração em sulcos diagonais; nenhum texto inscrito visível."
- inbcm-autor: null
- descricao-2: "Pistola de cano único, com cano de metal escuro oxidado e mecanismo de percussão com cão externo. A coronha é de madeira aparentemente entalhada, em tons de marrom e avermelhado, com sulcos diagonais e pequenos motivos repetidos na empunhadura. O guarda-mato e a capa da extremidade da coronha são de metal claro. Há oxidação no cano e na platina, manchas e desgaste no acabamento da madeira."
  evidence: "Cano e platina com ferrugem e manchas; coronha com decoração em sulcos diagonais; guarda-mato e extremidade da coronha em metal claro."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Entalhe"]
  evidence: ["Cano, platina, cão e guarda-mato metálicos; capa da extremidade da coronha em metal claro.","Coronha e parte da armação em madeira de tom marrom-avermelhado.","Coronha com sulcos e motivos decorativos aparentemente entalhados."]
- inbcm-conservacao: "Regular"
  evidence: "Oxidação visível no cano e na platina, manchas e desgaste na madeira, sem perdas graves aparentes que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Armas e acessórios"
  evidence: "Tesauro: classe de Pistola"
  tesauro: names=["Pistola","Pistola de percussão com coronha de madeira entalhada"] -> ["Pistola (exact 1)"]

## c12: 1 view (claude-sonnet-5-5; in 6262, out 1214, thinking 601, stop; system prompt 0 chars; images 1568x1046; 13.4 s)
**Denominação** ✗ (expected "Gargalheira") — **Classificação** ✗ (expected "Instrumentos de punição")

- inbcm-denominacao: "Argola"
  evidence: "Tesauro (exact: \"Argola\"): Amostras e fragmentos › Argola"
- titulo-2: "Argola de metal escuro com cinta e placa plana"
  evidence: "Sem texto inscrito; título baseado nas características físicas visíveis: cinta curva, argola e placa plana."
- inbcm-autor: null
- descricao-2: "Peça de metal escuro, aparentemente ferro forjado, composta por uma cinta larga e curva, aberta, e por uma argola circular com uma placa plana e retangular atravessada. As duas partes se unem por uma articulação com pequeno elo na extremidade. A superfície é irregular, com marcas de martelamento aparentes e pátina escura e opaca. Não há perdas ou fraturas visíveis."
  evidence: "Corpo metálico escuro com superfície irregular e texturizada; cinta curva à direita, argola com placa plana à esquerda, elo na extremidade direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Superfície escura e opaca, com aspecto metálico, aparentemente ferro, e marcas irregulares na cinta."]
- inbcm-conservacao: "Bom"
  evidence: "Peça íntegra, sem perdas, rasgos ou fraturas visíveis; apenas pátina escura e desgaste superficial."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Amostras e fragmentos"
  evidence: "Tesauro: classe de Argola"
  tesauro: names=["Argola","Argola de metal escuro com cinta e placa plana"] -> ["Argola (exact 1)"]

## c13: 1 view (claude-sonnet-5-5; in 6262, out 1196, thinking 589, stop; system prompt 0 chars; images 1568x1044; 12.4 s)
**Denominação** ✓ (expected "Fragmento") — **Classificação** ✓ 

- inbcm-denominacao: "Fragmento"
  evidence: "Tesauro (exact: \"Fragmento\"): Amostras e fragmentos › Fragmento"
- titulo-2: "Fragmento de relevo com buquê de flores amarrado por fita"
  evidence: "Motivo em relevo: flores, folhas e feixe de hastes preso por uma faixa com fitas pendentes."
- inbcm-autor: null
- descricao-2: "Fragmento de relevo em material aparentemente modelado ou moldado, de cor marrom-clara a marrom-escura. Mostra um buquê com rosas, folhas e hastes reunidas por uma faixa, da qual pendem fitas dobradas. As bordas são irregulares e quebradas. Há pequenas perdas nas pétalas e nas bordas, e resíduos esbranquiçados em algumas partes da superfície."
  evidence: "Relevo floral com feixe de hastes amarrado e fitas; bordas fraturadas; pétalas lascadas; manchas brancas pontuais na superfície."
- inbcm-dimensoes: null
- inbcm-mattecnica: []
  evidence: null
- inbcm-conservacao: "Regular"
  evidence: "Bordas fraturadas, pétalas lascadas e resíduos brancos na superfície; o motivo ainda é plenamente legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Amostras e fragmentos"
  evidence: "Tesauro: classe de Fragmento"
  tesauro: names=["Fragmento","Fragmento de relevo com buquê de flores amarrado por fita"] -> ["Fragmento (exact 1)"]

## c14: 1 view (claude-sonnet-5-5; in 6486, out 672, thinking 0, stop; system prompt 0 chars; images 1176x1568; 8.9 s)
**Denominação** ✓ (expected "Violão") — **Classificação** ✓ 

- inbcm-denominacao: "Violão"
  evidence: "Tesauro (exact: \"Violão\"): Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Cordofone › Violão"
- titulo-2: "Violão de madeira sem cordas com filetes decorativos"
  evidence: "Caixa e braço de madeira, sem cordas nem tarraxas, com filetes em padrão xadrez ao redor do tampo e da boca."
- inbcm-autor: null
- descricao-2: "Violão de madeira aparentemente envernizada, em tom marrom-alaranjado, com caixa em forma de oito, braço longo e cabeça com seis furos para tarraxas. Não tem cordas, tarraxas nem trastes metálicos; a escala apresenta divisões em relevo. O tampo tem filetes decorativos em padrão xadrez no contorno e ao redor da boca, e o cavalete tem incrustação clara com furos. Há marcas de desgaste e uma fenda visível no tampo, perto do braço."
  evidence: "Corpo, braço, cabeça com seis furos, boca circular com friso e cavalete com incrustação visíveis; ausência de cordas e tarraxas."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira"]
  evidence: ["Corpo, braço e cabeça com veios de madeira visíveis; os filetes decorativos parecem ser de madeira em tons contrastantes."]
- inbcm-conservacao: "Regular"
  evidence: "Instrumento completo, mas sem cordas e tarraxas, com desgaste e possível fenda no tampo perto da junção com o braço."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos associados à música"
  evidence: "Tesauro: classe de Violão"
  tesauro: names=["Violão","Violão de madeira sem cordas com filetes decorativos"] -> ["Violão (exact 1)"]

## c15: 1 view (claude-sonnet-5-5; in 6262, out 626, thinking 0, stop; system prompt 0 chars; images 1568x1044; 8.2 s)
**Denominação** ✓ (expected "Palmatória (castiçal)", or Castiçal) — **Classificação** ✓ 

- inbcm-denominacao: "Castiçal"
  evidence: "Tesauro (exact: \"Castiçal\"): Objetos domésticos › Objetos e equipamentos de iluminação › Castiçal"
- titulo-2: "Castiçal de metal com prato raso e alça em argola"
  evidence: "Prato circular com soquete central cilíndrico fendido e alça em argola; sem texto inscrito que indique função ou ocasião."
- inbcm-autor: null
- descricao-2: "Castiçal de metal aparentemente avermelhado, com prato circular raso de borda levemente elevada. Ao centro, soquete cilíndrico de tom dourado, com fenda vertical na lateral. Alça de tira metálica dobrada em argola presa à lateral do prato. A superfície apresenta manchas azuladas e acinzentadas, sinais de oxidação e desgaste, e pequenos rebites visíveis."
  evidence: "Forma, material e danos observados no objeto; sem inscrições legíveis no objeto (o número visível pertence à escala do museu e não foi transcrito)."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Corpo e alça de superfície metálica com aspecto acobreado e oxidação."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas, oxidação e desgaste da superfície visíveis, sem perdas estruturais evidentes."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de iluminação"
  evidence: "Tesauro: classe de Castiçal"
  tesauro: names=["Castiçal","Castiçal de metal com prato raso e alça em argola"] -> ["Castiçal (exact 1)"]

## c16: 1 view (claude-sonnet-5-5; in 6486, out 629, thinking 0, stop; system prompt 0 chars; images 1176x1568; 8.5 s)
**Denominação** ✗ (expected "Aldrava") — **Classificação** ✓ 

- inbcm-denominacao: "Espelho de fechadura"
  evidence: "Tesauro (exact: \"Espelho de fechadura\"): Estruturas edificadas e elementos de construção › Elementos de construção › Ferragem de esquadria › Fechadura (janela\/porta) › Espelho de fechadura"
- titulo-2: "Espelho de fechadura de metal com argola e haste"
  evidence: "Placa recortada com abertura de fechadura, argola curva na parte superior e haste cilíndrica atrás"
- inbcm-autor: null
- descricao-2: "Espelho de fechadura de metal aparentemente ferro, de contorno recortado e sinuoso, com abertura central em formato de buraco de fechadura e pequenos furos para fixação. Na parte superior, uma argola curva, aparentemente forjada, prende-se à placa, e uma haste cilíndrica está junto ao conjunto. A superfície apresenta corrosão avançada, com ferrugem e restos de uma camada clara, descascada em vários pontos."
  evidence: "Placa com furo central em formato de fechadura, argola superior e haste; superfície com ferrugem e resíduos esbranquiçados descamando"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Objeto metálico com oxidação marrom-alaranjada típica de ferro, visível na placa, na argola e na haste"]
- inbcm-conservacao: "Ruim"
  evidence: "Corrosão generalizada, perdas da camada superficial clara e superfície metálica desgastada e irregular em toda a placa, argola e haste"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Elementos de construção"
  evidence: "Tesauro: classe de Espelho de fechadura"
  tesauro: names=["Espelho de fechadura","Espelho de fechadura de metal com argola e haste"] -> ["Espelho de fechadura (exact 1)"]

## m1: 1 view (claude-sonnet-5-5; in 6262, out 776, thinking 0, stop; system prompt 0 chars; images 1568x1043; 8.8 s)
**Denominação** ✓ (expected "Sextante") — **Classificação** ✓ 

- inbcm-denominacao: "Sextante"
  evidence: "Tesauro (exact: \"Sextante\"): Equipamentos de atividades científicas e tecnológicas › Equipamentos associados à topografia e à navegação › Instrumento a reflexão › Sextante"
- titulo-2: "Sextante de metal com cabo de madeira e relógio acoplado"
  evidence: "Armação metálica escura com arco graduado dourado, cabo de madeira torneada e mostrador de relógio no centro."
- inbcm-autor: null
- descricao-2: "Sextante com armação de metal escurecido e arco graduado de metal dourado. Tem espelhos, uma luneta, uma lente de aumento e parafusos de metal amarelado. No centro, um cabo de madeira torneada de tom avermelhado sustenta um mostrador circular branco com ponteiros. O metal apresenta escurecimento e manchas, e a superfície mostra desgaste leve.\n\nInscrições: gravado, no arco graduado, parte inferior direita: [ilegível]; escala numérica gravada ao longo do arco, em parte ilegível."
  evidence: "Instrumento completo, com armação escura, arco dourado, cabo de madeira avermelhada e mostrador branco. Gravação fina no arco, parte inferior direita, ilegível."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Vidro","Torneado","Gravação"]
  evidence: ["Armação e arco de metal escurecido e dourado.","Cabo central de madeira avermelhada e envernizada.","Espelhos e lente de vidro.","Cabo de madeira com perfil torneado.","Escala e inscrição gravadas no arco graduado."]
- inbcm-conservacao: "Regular"
  evidence: "Escurecimento e manchas no metal, com desgaste visível, mas sem perdas que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos associados à topografia e à navegação"
  evidence: "Tesauro: classe de Sextante"
  tesauro: names=["Sextante","Sextante de metal com cabo de madeira e relógio acoplado"] -> ["Sextante (exact 1)"]

## m2: 1 view (claude-sonnet-5-5; in 7270, out 829, thinking 0, stop; system prompt 0 chars; images 1568x1568; 10.2 s)
**Denominação** ✓ (expected "Moeda") — **Classificação** ✓ 

- inbcm-denominacao: "Moeda"
  evidence: "Tesauro (exact: \"Moeda\"): Objetos pecuniários › Dinheiro › Moeda"
- titulo-2: "Moeda de metal prateado com a inscrição 2000 Réis"
  evidence: "Valor \"2000 RÉIS\" ao centro inferior; legenda \"ORDEM E PROGRESSO\" ao longo da borda"
- inbcm-autor: null
- descricao-2: "Moeda circular de metal de cor prateada, aparentemente cunhada, com borda serrilhada. Ao centro superior há um brasão com estrela de cinco pontas, cercado por raios e por uma faixa com texto. Abaixo, o valor \"2000 RÉIS\" é ladeado por ramos de folhas e frutos. A superfície apresenta desgaste, riscos e manchas escuras, sobretudo na borda.\n\nInscrições: cunhado em relevo, ao longo da borda superior: \"ORDEM E PROGRESSO\"; ao centro inferior: \"2000 \/ RÉIS\"; na faixa abaixo do brasão: \"[ilegível]NOVU[?] \/ [ilegível]899[?]\""
  evidence: "Moeda circular com serrilha na borda; legenda \"ORDEM E PROGRESSO\" na borda; \"2000 RÉIS\" ao centro inferior; faixa sob o brasão com letras pouco legíveis; desgaste e manchas na superfície"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Gravação"]
  evidence: ["Disco de metal de cor prateada com aspecto metálico","Inscrições e motivos em relevo, aparentemente cunhados ou gravados em matriz"]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste do relevo, riscos, manchas escuras e pequenas deformações na borda, sem comprometer a leitura do valor e da legenda"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Moeda"
  tesauro: names=["Moeda","Moeda de metal prateado com a inscrição 2000 Réis"] -> ["Moeda (exact 1)"]

## m3: 1 view (claude-sonnet-5-5; in 6262, out 707, thinking 0, stop; system prompt 0 chars; images 1568x1043; 8.8 s)
**Denominação** ✓ (expected "Carimbo", or Carimbo (selo)) — **Classificação** ✓ 

- inbcm-denominacao: "Carimbo"
  evidence: "Tesauro (exact: \"Carimbo\"): Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo. Alternativas: Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo › Carimbo (selo); Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Matriz de selo › Carimbo (selo)"
- titulo-2: "Carimbo de madeira com cabo preto e base vermelha"
  evidence: "Cabo preto em forma de pegador sobre base quadrada vermelho-amarronzada; nenhuma inscrição legível indica função específica"
- inbcm-autor: null
- descricao-2: "Carimbo manual com cabo em forma de balaústre, aparentemente de madeira torneada e pintada ou envernizada de preto. A base é quadrada, aparentemente de madeira, com acabamento vermelho-amarronzado nas laterais. Uma das laterais está sem o acabamento e mostra a madeira clara, com marcas gravadas ou carimbadas. O cabo apresenta pequenas falhas e desgaste na tinta, e a base tem arranhões e manchas leves.\n\nInscrições: gravado ou carimbado, em uma das laterais da base: [ilegível]"
  evidence: "Cabo preto torneado, base quadrada vermelha; lateral direita da base em madeira clara com marcas de texto ilegíveis"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Torneado"]
  evidence: ["Cabo e base com aspecto de madeira, com veios visíveis na base e na lateral clara","Cabo com perfil arredondado e simétrico, aparentemente torneado"]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste e pequenas perdas na superfície preta do cabo e da base, arranhões e manchas na base vermelha, sem comprometer a leitura do objeto"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação escrita"
  evidence: "Tesauro: classe de Carimbo"
  tesauro: names=["Carimbo","Carimbo de madeira com cabo preto e base vermelha"] -> ["Carimbo (exact 1)","Carimbo (selo) (exact 0.98)","Carimbo (selo) (exact 0.98)"]

## m4: 1 view (claude-sonnet-5-5; in 6710, out 636, thinking 0, stop; system prompt 0 chars; images 1568x1262; 8.5 s)
**Denominação** ✓ (expected "Prato", or Prato decorativo) — **Classificação** ✓ 

- inbcm-denominacao: "Prato"
  evidence: "Tesauro (exact: \"Prato\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Prato. Alternativas: Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Membranofone › Bateria › Prato (música); Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Idiofone › Prato (música)"
- titulo-2: "Prato de louça branca com guirlanda floral pintada"
  evidence: "Prato branco com borda azul fina e guirlanda de rosas e margaridas pintadas em rosa, azul, laranja e amarelo."
- inbcm-autor: null
- descricao-2: "Prato circular raso, de superfície branca e brilhante, aparentemente de porcelana, com filete azul fino na borda. Guirlanda de quatro rosas rosa-escuras alternadas com margaridas azuis, laranja e amarelas e folhas verdes, aparentemente pintada à mão, deixa o centro livre. Há pequenas perdas de esmalte ou da tinta da borda, no alto à direita e embaixo à esquerda."
  evidence: "Prato visto de cima; pinceladas visíveis nas flores; pequenas falhas na borda azul, no alto à direita e embaixo à esquerda."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Porcelana","Pintura"]
  evidence: ["Corpo branco, liso e vitrificado, aparentemente de porcelana.","Flores e folhas com pinceladas visíveis, aparentemente pintadas à mão."]
- inbcm-conservacao: "Regular"
  evidence: "Pequenas falhas visíveis na borda azul, no alto à direita e embaixo à esquerda; decoração íntegra."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Prato"
  tesauro: names=["Prato","Prato de louça branca com guirlanda floral pintada"] -> ["Prato (exact 1)","Prato (música) (exact 0.98)","Prato (música) (exact 0.98)"]

