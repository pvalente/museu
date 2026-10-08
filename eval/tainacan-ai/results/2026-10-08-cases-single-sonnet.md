applied preamble.txt (2519 chars)
Success: Applied inbcm-museologico.json
mode: single (first view, REST endpoint), model: site default, effort: default, max_tokens: site, museu_ai_image_max_edge: 0 (originals sent)
collection: Acervo Museológico (#127)

**Pass: 24/28** (Denominação and Classificação both right). Denominação 24/28, Classificação 26/28, Data de Produção 26/28 (informational).
Tokens: 186682 in + 31206 out over 28 requests (mean 6667 in, 1114 out).

| Case | Views | Denominação | Expected | ✓ | Classificação | ✓ | Data de Produção (got / expected) | Autor | Tokens in/out |
|---|---|---|---|---|---|---|---|---|---|
| e1 | 1 | Escultura | Figura animal (escultura) | ✓ alt | Objetos associados às artes plásticas e ao desenho técnico | ✓ | — / — ✓ | — | 8737/693 |
| e2 | 1 | — | — | ✓ null | — | ✓ | 24-12-1914 / 24-12-1914 ✓ | — | 7171/2015 |
| e3 | 1 | Placa (condecoração) | Placa comemorativa | ✗ | Insígnias | ✗ | 26/9/03 / 26/9/03 ✓ | — | 7102/1627 |
| e4 | 1 | Bule | Bule | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 7240/1397 |
| e5 | 1 | Cesta | Cesta | ✓ exact | Recipientes | ✓ | — / — ✓ | — | 7033/694 |
| e6 | 1 | Ex-voto | Ex-voto | ✓ exact | Objetos rituais e cerimoniais | ✓ | 1766 / 1766 ✓ | — | 7516/2260 |
| e7 | 1 | Tigela | Tigela | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 7792/648 |
| e8 | 1 | — | — | ✓ null | — | ✓ | — / — ✓ | — | 7171/663 |
| c01 | 1 | Azulejo | Azulejo | ✓ exact | Elementos de construção | ✓ | — / — ✓ | — | 6491/693 |
| c02 | 1 | Leque | Leque | ✓ exact | Objetos de auxílio, cuidados e conforto pessoais | ✓ | — / — ✓ | — | 6491/700 |
| c03 | 1 | Chapéu | Chapéu | ✓ exact | Vestuário | ✓ | — / — ✓ | — | 6259/652 |
| c04 | 1 | Plaina | Plaina | ✓ exact | Equipamento de atividades de transformação | ✓ | — / — ✓ | — | 6259/703 |
| c05 | 1 | Foice | Foice | ✓ exact | Equipamentos de agricultura, pecuária e pesca | ✓ | — / — ✓ | — | 6491/1399 |
| c06 | 1 | Barômetro | Barômetro | ✓ exact | Equipamento associado à meteorologia | ✓ | — / — ✓ | — | 6259/909 |
| c07 | 1 | Gramofone | Gramofone | ✓ exact | Equipamento de comunicação sonora | ✓ | — / — ✓ | — | 6259/1316 |
| c08 | 1 | Pião | Pião | ✓ exact | Equipamento lúdico | ✓ | — / — ✓ | — | 6259/1338 |
| c09 | 1 | Cédula | Cédula | ✓ exact | Dinheiro | ✓ | — / — ✓ | American Bank Note Co.New York | 5389/1971 |
| c10 | 1 | Estribo | Estribo | ✓ exact | Veículos terrestres e acessórios | ✓ | — / — ✓ | — | 6491/604 |
| c11 | 1 | Pistola | Pistola | ✓ exact | Armas e acessórios | ✓ | — / — ✓ | — | 6085/691 |
| c12 | 1 | Grilhão | Gargalheira | ✗ | Instrumentos de punição | ✓ | — / — ✓ | — | 6259/1206 |
| c13 | 1 | Relevo | Fragmento | ✗ | Objetos associados às artes plásticas e ao desenho técnico | ✗ | — / — ✓ | — | 6259/1471 |
| c14 | 1 | Violão | Violão | ✓ exact | Objetos associados à música | ✓ | — / — ✓ | — | 6491/1471 |
| c15 | 1 | Castiçal | Palmatória (castiçal) | ✓ alt | Objetos e equipamentos de iluminação | ✓ | — / — ✓ | — | 6259/613 |
| c16 | 1 | Espelho de fechadura | Aldrava | ✗ | Elementos de construção | ✓ | — / — ✓ | — | 6491/627 |
| m1 | 1 | Sextante | Sextante | ✓ exact | Equipamentos associados à topografia e à navegação | ✓ | — / 1922 ✗ | — | 6201/1580 |
| m2 | 1 | Moeda | Moeda | ✓ exact | Dinheiro | ✓ | — / 1913 ✗ | — | 7361/1482 |
| m3 | 1 | Carimbo | Carimbo | ✓ exact | Equipamento de comunicação escrita | ✓ | — / — ✓ | — | 6201/1129 |
| m4 | 1 | Prato | Prato | ✓ exact | Objetos e equipamentos de serviço de alimentos | ✓ | — / — ✓ | — | 6665/654 |

## e1: 1 view (claude-sonnet-5-5; in 8737, out 693, thinking 0, stop; system prompt 9117 chars; images 2560x1920; 12 s)
**Denominação** ✓ (expected "Figura animal (escultura)", or Escultura) — **Classificação** ✓ 

- inbcm-denominacao: "Escultura"
  evidence: "Tesauro (exact: \"Escultura\"): Objetos de atividades artísticas › Objetos associados às artes plásticas e ao desenho técnico › Escultura"
- titulo-2: "Escultura de tucano em madeira com bico bicolor"
  evidence: "Figura de ave de bico grande e curvo, com bico em tom claro e manchas escuras, corpo em tom dourado-escuro e olho em círculos concêntricos pretos."
- inbcm-autor: null
- descricao-2: "Escultura de ave com bico grande e curvo, aparentemente entalhada em madeira. O bico tem a madeira clara aparente, com manchas escuras. A cabeça é escura, com face clara e olho em círculos concêntricos pretos. O corpo tem acabamento dourado-escuro e os pés são lâminas planas com listras pretas. O bico apresenta uma pequena lasca na borda superior e uma rachadura fina na parte inferior."
  evidence: "Observação direta do objeto: bico com lasca na borda superior e fissura na parte inferior, corpo com acabamento dourado, olho pintado ou gravado em círculos concêntricos."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Entalhe","Pintura"]
  evidence: ["Veios e textura de madeira visíveis no bico e na face.","Volumes facetados e planos com marcas de corte sugerem entalhe.","Olho, manchas escuras e acabamento dourado do corpo aparentam ser pintados ou tingidos."]
- inbcm-conservacao: "Regular"
  evidence: "Pequena lasca na borda superior do bico e fissura fina na parte inferior, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos associados às artes plásticas e ao desenho técnico"
  evidence: "Tesauro: classe de Escultura"
  tesauro: names=["Escultura","Escultura de tucano em madeira com bico bicolor"] -> ["Escultura (exact 1)"]

## e2: 1 view (claude-sonnet-5-5; in 7171, out 2015, thinking 1102, stop; system prompt 9117 chars; images 1920x1276; 17 s)
**Denominação** ✓ (expected null) — **Classificação** ✓ 

- inbcm-denominacao: null
  new terms: ["Cartão"]
- titulo-2: "Cartão manuscrito de boas festas"
  evidence: "Texto manuscrito: \"desejando bôas festas e innumeras \/ prosperidades no decorrer do anno \/ novo\"."
- inbcm-autor: null
- descricao-2: "Cartão retangular de papel marrom, com manchas escuras e pontos esbranquiçados espalhados. Apresenta mensagem manuscrita em tinta preta, em letra cursiva, e texto impresso em cinza claro, desbotado. Os cantos têm dobras e pequenas perdas, e há um vinco horizontal na parte superior.\n\nInscrições: manuscrito, na parte superior e central: \"Ao bondoso amigo Lula \/ o \/ Carlito[?] abraça \/ desejando bôas festas e innumeras \/ prosperidades no decorrer do anno \/ novo\"; impresso, desbotado, sob o manuscrito central: \"Carl[?] J[?] Avancini[?]\"; impresso, canto inferior esquerdo: \"Villa de Santa Thereza\"; manuscrito, na parte inferior: \"24-12-1914\"; impresso, desbotado, canto inferior direito: \"E. E. Santo[?]\"."
  evidence: "Cartão de papel marrom com manuscrito preto, texto impresso desbotado sob a escrita e nos cantos inferiores; manchas, dobras nos cantos e vinco no alto."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Escrita manual","Impressão"]
  evidence: ["Suporte de papel marrom com bordas irregulares.","Mensagem e data em tinta preta, em letra cursiva manuscrita.","Texto impresso desbotado: \"Villa de Santa Thereza\" e nome impresso sob o manuscrito."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras generalizadas, pontos brancos, vinco horizontal no alto e cantos dobrados ou com pequenas perdas, sem comprometer a leitura."
- inbcm-localprod: null
- inbcm-dataprod: "24-12-1914"
  evidence: "Data manuscrita na parte inferior: \"24-12-1914\"."
  tesauro: names=["Cartão","Cartão manuscrito de boas festas"] -> []

## e3: 1 view (claude-sonnet-5-5; in 7102, out 1627, thinking 918, stop; system prompt 9117 chars; images 1920x1257; 14.8 s)
**Denominação** ✗ (expected "Placa comemorativa") — **Classificação** ✗ (expected "Objetos cerimoniais e\/ou comemorativos")

- inbcm-denominacao: "Placa (condecoração)"
  evidence: "Tesauro (exact: \"Placa\"): Insígnias e objetos cerimoniais e comemorativos › Insígnias › Condecoração › Placa (condecoração). Alternativas: Placa (relevo) — Objetos de atividades artísticas › Objetos associados às artes plásticas e ao desenho técnico › Escultura › Relevo › Placa (relevo)"
- titulo-2: "Placa gravada com saudação a Santos Dumont"
  evidence: "Texto gravado: \"Ao glorioso aeronauta \/ Dr. Santos Dumont\"."
- inbcm-autor: null
- descricao-2: "Placa retangular de metal de cor dourada, aparentemente gravada à mão. Moldura de volutas e ornamentos vegetais finamente gravados nas bordas. Texto em letra cursiva gravada ocupa o centro da placa. A superfície apresenta pequenos riscos e uma pequena mancha escura próxima ao centro.\n\nInscrições: gravado, ao centro: \"Ao glorioso aeronauta \/ Dr. Santos Dumont \/ O sport nautico[?] da \/ Bahia, sauda[?] a vossa \/ passagem por esta \/ Capital.\"; gravado, abaixo à direita: \"26\/9\/03\"."
  evidence: "Aspecto geral da placa; texto cursivo gravado e moldura ornamental nas bordas; riscos leves e pequeno ponto escuro no centro."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Gravação"]
  evidence: ["Superfície metálica dourada, aparentemente latão ou liga semelhante.","Texto cursivo e moldura com linhas incisas, aparentemente gravadas."]
- inbcm-conservacao: "Bom"
  evidence: "Apenas riscos superficiais leves e um pequeno ponto escuro; inscrição e ornamentos totalmente legíveis."
- inbcm-localprod: null
- inbcm-dataprod: "26\/9\/03"
  evidence: "Data gravada abaixo à direita: \"26\/9\/03\"."
- inbcm-classificacao: "Insígnias"
  evidence: "Tesauro: classe de Placa (condecoração)"
  tesauro: names=["Placa","Placa gravada com saudação a Santos Dumont"] -> ["Placa (condecoração) (exact 0.98)","Placa (relevo) (exact 0.98)"]

## e4: 1 view (claude-sonnet-5-5; in 7240, out 1397, thinking 752, stop; system prompt 9117 chars; images 1920x1306; 13 s)
**Denominação** ✓ (expected "Bule", or Bule de chá, Bule de café) — **Classificação** ✓ 

- inbcm-denominacao: "Bule"
  evidence: "Tesauro (exact: \"Bule\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Bule. Alternativas: Bule (samovar) — Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Bule › Bule (samovar)"
- titulo-2: "Bule de metal com monograma gravado"
  evidence: "Monograma gravado em cartucho no centro do corpo; corpo e tampa metálicos."
- inbcm-autor: null
- descricao-2: "Bule de corpo arredondado, mais largo na parte inferior, com tampa articulada por dobradiça e botão de preensão decorado. O metal é escuro, aparentemente prateado e oxidado, com superfície texturizada. Bico cilíndrico e alça curva com ornatos em relevo e dois anéis claros, possivelmente de marfim ou osso. A base tem metal de tom cobreado, e a tampa apresenta manchas escuras.\n\nInscrições: gravado, em cartucho decorado no centro do corpo: monograma de letras entrelaçadas [ilegível]"
  evidence: "Corpo, tampa, alça e bico visíveis; cartucho gravado no centro do corpo; manchas na tampa; base de tom cobreado."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Gravação"]
  evidence: ["Corpo, tampa, bico e alça metálicos, com escurecimento por oxidação.","Cartucho decorado com monograma e ornatos gravados no corpo."]
- inbcm-conservacao: "Regular"
  evidence: "Escurecimento generalizado do metal e manchas na tampa, sem perdas ou fraturas visíveis."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Bule"
  tesauro: names=["Bule","Bule de metal com monograma gravado"] -> ["Bule (exact 1)","Bule (samovar) (exact 0.98)"]

## e5: 1 view (claude-sonnet-5-5; in 7033, out 694, thinking 0, stop; system prompt 9117 chars; images 1920x1206; 8.2 s)
**Denominação** ✓ (expected "Cesta") — **Classificação** ✓ 

- inbcm-denominacao: "Cesta"
  evidence: "Tesauro (exact: \"Cesta\"): Recipientes › Cesta"
- titulo-2: "Cesta oval com tampa de tiras vegetais amarradas"
  evidence: "Corpo oval baixo formado por tiras verticais claras, tampa plana e amarração com fibra; sem texto inscrito."
- inbcm-autor: null
- descricao-2: "Cesta oval baixa com tampa, de fibra vegetal aparentemente em tiras estreitas, claras, dispostas na vertical. A borda e a tampa são reforçadas por varetas finas, presas com fio vegetal. As tiras da tampa parecem se abrir em leque junto a uma das bordas. Um fio solto pende na lateral esquerda e há pequenas folgas e desfiamentos nas tiras e nas amarrações."
  evidence: "Corpo oval de tiras verticais, tampa plana com varetas e amarração, fio solto à esquerda, tiras desalinhadas na base e na borda."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Fibra vegetal","Trançado"]
  evidence: ["Tiras e fios de aspecto vegetal compõem o corpo, a tampa e as amarrações.","Tiras entrelaçadas e amarradas formam a estrutura; trançado visível na cesta ao fundo e amarração na peça principal."]
- inbcm-conservacao: "Regular"
  evidence: "Fio solto na lateral esquerda, tiras levemente desalinhadas e desfiadas na base e na borda, sem perdas que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Recipientes"
  evidence: "Tesauro: classe de Cesta"
  tesauro: names=["Cesta","Cesta oval com tampa de tiras vegetais amarradas"] -> ["Cesta (exact 1)"]

## e6: 1 view (claude-sonnet-5-5; in 7516, out 2260, thinking 1319, stop; system prompt 9117 chars; images 1920x1417; 19.4 s)
**Denominação** ✓ (expected "Ex-voto") — **Classificação** ✓ 

- inbcm-denominacao: "Ex-voto"
  evidence: "Tesauro (exact: \"Ex-voto\"): Objetos de ritos, cultos e crenças › Objetos rituais e cerimoniais › Ex-voto"
- titulo-2: "Ex-voto pintado com legenda manuscrita"
  evidence: "Pintura emoldurada com legenda manuscrita de agradecimento na faixa inferior."
- inbcm-autor: null
- descricao-2: "Pintura emoldurada, aparentemente sobre madeira, com cena interna em tons de marrom, azul e verde. Uma mulher de cabelos longos está deitada em um leito, cercada por mulheres de hábito escuro e véu e por três homens de casaca à esquerda. Em primeiro plano, uma figura ajoelhada de costas; à direita, figuras sobre nuvens, uma delas com auréola e manto azul. Uma fissura horizontal atravessa a pintura, e a moldura de madeira vermelho-escura apresenta craquelê.\n\nInscrições: manuscrito, faixa inferior: \"Merce[?] q fez N. S. do Desti[?]. de soror D. Anna Jozefa dos Anjos[?], Estando gravem.te enferma \/ de hua matina daqual Sevio Livre, Não sei[?] julgando mais Vida, p.r entercessão Da mes- \/ ma Senr.a Graças a DEOS para Sempre Ano de 1766\""
  evidence: "Cena pintada com fissura horizontal visível; moldura vermelho-escura com craquelê; texto manuscrito em tinta preta e vermelha na faixa inferior."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Pintura","Escrita manual"]
  evidence: ["Suporte com fissura horizontal e moldura de madeira aparentemente entalhada em perfis escalonados.","Cena figurativa em cores, aparentemente pintada.","Legenda manuscrita em tinta preta e vermelha na faixa inferior."]
- inbcm-conservacao: "Regular"
  evidence: "Fissura horizontal visível atravessando a pintura e craquelê na moldura, sem comprometer a leitura da cena e da legenda."
- inbcm-localprod: null
- inbcm-dataprod: "1766"
  evidence: "Legenda manuscrita na faixa inferior, lado direito: \"Ano de 1766\"."
- inbcm-classificacao: "Objetos rituais e cerimoniais"
  evidence: "Tesauro: classe de Ex-voto"
  tesauro: names=["Ex-voto","Ex-voto pintado com legenda manuscrita"] -> ["Ex-voto (exact 1)"]

## e7: 1 view (claude-sonnet-5-5; in 7792, out 648, thinking 0, stop; system prompt 9117 chars; images 1920x1515; 8.5 s)
**Denominação** ✓ (expected "Tigela", or Vasilha) — **Classificação** ✓ 

- inbcm-denominacao: "Tigela"
  evidence: "Tesauro (exact: \"Tigela\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Tigela"
- titulo-2: "Tigela de cerâmica com pintura geométrica em preto e vermelho"
  evidence: "Corpo cerâmico com pintura geométrica em preto e vermelho sobre fundo claro, visível no interior e no exterior. Não há texto inscrito."
- inbcm-autor: null
- descricao-2: "Tigela de boca ampla e corpo baixo, em cerâmica aparentemente pintada. O interior e o exterior têm decoração geométrica sobre fundo claro, com linhas largas pretas, filetes finos vermelhos e pequenos traços pretos preenchendo os campos. No exterior há faixas em espiral e áreas pretas. Na borda há uma trinca ou fratura com resíduos esbranquiçados, e há desgaste e manchas na superfície."
  evidence: "Forma, pintura e danos observados na borda e na superfície da peça."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Cerâmica","Pintura"]
  evidence: ["Corpo aparentemente cerâmico, com superfície opaca e bordas fraturadas.","Motivos geométricos em preto e vermelho aparentemente pintados sobre fundo claro."]
- inbcm-conservacao: "Regular"
  evidence: "Trinca ou fratura com resíduos esbranquiçados na borda, além de desgaste e manchas, sem comprometer a leitura da peça."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Tigela"
  tesauro: names=["Tigela","Tigela de cerâmica com pintura geométrica em preto e vermelho"] -> ["Tigela (exact 1)"]

## e8: 1 view (claude-sonnet-5-5; in 7171, out 663, thinking 0, stop; system prompt 9117 chars; images 1920x1277; 7.7 s)
**Denominação** ✓ (expected null) — **Classificação** ✓ 

- inbcm-denominacao: null
  new terms: ["Cartão"]
- titulo-2: "Cartão de papel marrom com manuscrito espelhado"
  evidence: "Papel retangular marrom com escrita manuscrita em tinta escura, com letras invertidas."
- inbcm-autor: null
- descricao-2: "Cartão retangular de papel marrom, com cantos levemente danificados e manchas escuras por toda a superfície. Apresenta pequena fenda horizontal próxima ao centro superior e alguns pontos brancos de perda ou resíduo. Há escrita manuscrita em tinta escura, em várias linhas, com letras espelhadas, o que indica que o texto transparece do outro lado do papel.\n\nInscrições: manuscrito espelhado, transparecendo do outro lado, não transcrito."
  evidence: "Texto manuscrito com letras invertidas em várias linhas; papel marrom manchado, com fenda e cantos danificados."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Escrita manual"]
  evidence: ["Suporte de papel marrom visível, com fibras e manchas.","Texto em tinta escura de aparência manuscrita, com traços cursivos."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras generalizadas, fenda no papel, cantos danificados e pontos brancos, mas a escrita permanece legível."
- inbcm-localprod: null
- inbcm-dataprod: null
  tesauro: names=["Cartão","Cartão de papel marrom com manuscrito espelhado"] -> []

## c01: 1 view (claude-sonnet-5-5; in 6491, out 693, thinking 0, stop; system prompt 9117 chars; images 1200x1600; 8.5 s)
**Denominação** ✓ (expected "Azulejo") — **Classificação** ✓ 

- inbcm-denominacao: "Azulejo"
  evidence: "Tesauro (exact: \"Azulejo\"): Estruturas edificadas e elementos de construção › Elementos de construção › Azulejo"
- titulo-2: "Azulejo com motivo floral pintado em azul e amarelo"
  evidence: "Motivo floral central em azul e amarelo, dentro de um contorno sinuoso sobre fundo branco, com cantos em tons de azul."
- inbcm-autor: null
- descricao-2: "Azulejo quadrado de cerâmica aparentemente esmaltada, com decoração pintada à mão. Ao centro, uma flor estilizada com pétalas em azul e miolo amarelo, cercada por pequenos traços azuis, em reserva branca de contorno sinuoso. Os cantos são pintados em tons de azul. Apresenta uma linha de fratura diagonal que cruza a face decorada, manchas amareladas, pequenas perdas de esmalte e irregularidades nas bordas."
  evidence: "Peça quadrada com flor central azul e amarela; linha diagonal de fratura visível na face; manchas e lascas de esmalte nas bordas superior e inferior."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Cerâmica","Pintura"]
  evidence: ["Corpo cerâmico com face esmaltada e brilhante, aparentemente de cerâmica.","Motivo floral e contornos aparentemente pintados à mão em azul, amarelo e verde."]
- inbcm-conservacao: "Regular"
  evidence: "Linha de fratura diagonal na face, manchas amareladas, pequenas perdas de esmalte e bordas irregulares, sem comprometer a leitura do motivo."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Elementos de construção"
  evidence: "Tesauro: classe de Azulejo"
  tesauro: names=["Azulejo","Azulejo com motivo floral pintado em azul e amarelo"] -> ["Azulejo (exact 1)"]

## c02: 1 view (claude-sonnet-5-5; in 6491, out 700, thinking 0, stop; system prompt 9117 chars; images 1600x1200; 8.7 s)
**Denominação** ✓ (expected "Leque") — **Classificação** ✓ 

- inbcm-denominacao: "Leque"
  evidence: "Tesauro (exact: \"Leque\"): Objetos de uso pessoal › Objetos de auxílio, cuidados e conforto pessoais › Leque"
- titulo-2: "Leque de folha pintada com flores e borboletas"
  evidence: "Folha plissada com flores (margaridas, flor vermelha, flores azuis) e duas borboletas pintadas; varetas vazadas."
- inbcm-autor: null
- descricao-2: "Leque dobrável com folha plissada em tom bege, aparentemente de tecido ou papel, pintada com margaridas brancas, flores azuis, uma flor vermelha e duas borboletas azul-esverdeadas. As varetas, de material aparentemente escuro, têm a parte inferior vazada com motivos ornamentais; as duas varetas externas parecem decoradas com incisões. A folha apresenta manchas e escurecimento. Há uma etiqueta amarrada por fio na base e uma peça separada, alongada e ornamentada, ao lado do leque."
  evidence: "Folha plissada pintada à mão, varetas vazadas, etiqueta presa à base, peça solta alongada; manchas visíveis na folha."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Tecido","Madeira","Pintura"]
  evidence: ["Folha plissada com aspecto de tecido (possivelmente seda ou papel; não confirmado).","Varetas escuras e vazadas, aparentemente de madeira.","Flores e borboletas aparentemente pintadas à mão sobre a folha."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas e escurecimento na folha, desgaste nas varetas e peça solta separada; leque ainda íntegro e legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos de auxílio, cuidados e conforto pessoais"
  evidence: "Tesauro: classe de Leque"
  tesauro: names=["Leque","Leque de folha pintada com flores e borboletas"] -> ["Leque (exact 1)"]

## c03: 1 view (claude-sonnet-5-5; in 6259, out 652, thinking 0, stop; system prompt 9117 chars; images 1600x1065; 7.5 s)
**Denominação** ✓ (expected "Chapéu", or Chapéu-panamá) — **Classificação** ✓ 

- inbcm-denominacao: "Chapéu"
  evidence: "Tesauro (exact: \"Chapéu\"): Objetos de uso pessoal › Vestuário › Cobertura de cabeça › Chapéu"
- titulo-2: "Chapéu de fibra trançada com faixa preta"
  evidence: "Copa e aba em fibra aparentemente trançada, de cor bege; faixa escura em tecido ao redor da base da copa."
- inbcm-autor: null
- descricao-2: "Chapéu de aba larga e copa alta, em fibra vegetal aparentemente trançada, de cor bege. A copa apresenta um vinco e uma depressão na parte superior. Uma faixa larga de tecido preto contorna a base da copa. A faixa apresenta rasgos, perdas e fios soltos, principalmente à esquerda, e há pequenas manchas e marcas no trançado."
  evidence: "Trançado fino visível na copa e na aba; faixa preta com bordas rasgadas e perdas à esquerda; copa deformada no topo."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Fibra vegetal","Trançado","Tecido"]
  evidence: ["Copa e aba com aparência de palha ou fibra vegetal de cor bege.","Trama fina e regular visível na copa e na aba, compatível com trançado.","Faixa preta de aparência têxtil ao redor da copa."]
- inbcm-conservacao: "Regular"
  evidence: "Faixa preta com rasgos e perdas à esquerda, copa deformada e pequenas manchas; o objeto continua legível e íntegro."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Vestuário"
  evidence: "Tesauro: classe de Chapéu"
  tesauro: names=["Chapéu","Chapéu de fibra trançada com faixa preta"] -> ["Chapéu (exact 1)"]

## c04: 1 view (claude-sonnet-5-5; in 6259, out 703, thinking 0, stop; system prompt 9117 chars; images 1600x1067; 8.1 s)
**Denominação** ✓ (expected "Plaina", or Plaina manual) — **Classificação** ✓ 

- inbcm-denominacao: "Plaina"
  evidence: "Tesauro (exact: \"Plaina\"): Equipamento de atividades produtivas › Equipamento de atividades de transformação › Plaina"
- titulo-2: "Plaina de madeira com cunha e pino transversal"
  evidence: "Corpo retangular de madeira com cunha encaixada na abertura central e pino cilíndrico transversal em uma das extremidades."
- inbcm-autor: null
- descricao-2: "Plaina manual de madeira, de corpo retangular alongado, aparentemente entalhada. Possui abertura central trapezoidal, onde se encaixam uma cunha de madeira com pequenas incisões em linha e uma lâmina metálica. Em uma extremidade há um pino cilíndrico de madeira com rachadura visível na face. A superfície apresenta manchas escuras, desgaste, pequenas fissuras e fios de fibra aderidos."
  evidence: "Objeto de madeira marrom-clara com cunha, lâmina e pino cilíndrico visíveis; manchas escuras na metade esquerda; rachadura na face do pino."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Entalhe","Metal"]
  evidence: ["Corpo, cunha e pino em madeira com veios aparentes.","Peças aparentemente entalhadas, com incisões decorativas em linha na cunha e na borda da abertura.","Lâmina escura visível na abertura central, aparentemente metálica."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas escuras, desgaste superficial, pequenas fissuras e rachadura no pino, sem perdas que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de atividades de transformação"
  evidence: "Tesauro: classe de Plaina"
  tesauro: names=["Plaina","Plaina de madeira com cunha e pino transversal"] -> ["Plaina (exact 1)"]

## c05: 1 view (claude-sonnet-5-5; in 6491, out 1399, thinking 694, stop; system prompt 9117 chars; images 1200x1600; 12.8 s)
**Denominação** ✓ (expected "Foice") — **Classificação** ✓ 

- inbcm-denominacao: "Foice"
  evidence: "Tesauro (exact: \"Foice\"): Equipamento de atividades produtivas › Equipamentos de agricultura, pecuária e pesca › Foice"
- titulo-2: "Foice de lâmina metálica curva com cabo de madeira"
  evidence: "Forma da lâmina e do cabo; não há texto legível que indique função ou ocasião"
- inbcm-autor: null
- descricao-2: "Foice com lâmina curva em forma de crescente, de metal aparentemente ferro, com ponta aguçada e espiga inserida em cabo de madeira cônico. A lâmina apresenta corrosão generalizada, manchas claras e marrons e três pequenas perfurações próximas à base. O cabo, de madeira em tom claro, tem manchas e pequenas marcas de tinta.\n\nInscrições: marca gravada ou estampada na espiga, junto ao cabo: \"[ilegível]\"."
  evidence: "Lâmina metálica corroída com furos na região inferior; cabo de madeira cônico; marca pequena na espiga junto ao cabo"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira"]
  evidence: ["Lâmina curva de metal com corrosão superficial","Cabo cônico de madeira em tom claro"]
- inbcm-conservacao: "Regular"
  evidence: "Corrosão generalizada e manchas na lâmina, além de três pequenas perfurações perto da base; a forma do objeto permanece íntegra e legível"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos de agricultura, pecuária e pesca"
  evidence: "Tesauro: classe de Foice"
  tesauro: names=["Foice","Foice de lâmina metálica curva com cabo de madeira"] -> ["Foice (exact 1)"]

## c06: 1 view (claude-sonnet-5-5; in 6259, out 909, thinking 0, stop; system prompt 9117 chars; images 1600x1067; 8.8 s)
**Denominação** ✓ (expected "Barômetro", or Barômetro aneróide) — **Classificação** ✓ 

- inbcm-denominacao: "Barômetro"
  evidence: "Tesauro (exact: \"Barômetro\"): Equipamentos de atividades científicas e tecnológicas › Equipamento associado à meteorologia › Barômetro"
- titulo-2: "Barômetro aneroide com moldura circular de madeira escurecida"
  evidence: "Mecanismo visível sob vidro no centro; moldura circular escalonada, escura e brilhante, com friso de dentes entalhados; argola metálica no topo."
- inbcm-autor: null
- descricao-2: "Barômetro circular de parede, com moldura de madeira aparentemente torneada e entalhada, escalonada, com friso de dentes e acabamento preto brilhante. Mostrador prateado sob vidro, com escalas numéricas, dois ponteiros e mecanismo metálico aparente ao centro. Argola metálica dourada no topo. A moldura apresenta pequenas perdas e desgaste do acabamento nas bordas, e o mostrador tem manchas.\n\nInscrições: impresso, no mostrador: \"TEMPESTAD\" \/ \"Gº LLUVIA.\" \/ \"LLUVIA Y VIENTO.\" \/ \"VARIABLE.\" \/ \"BUEN TIEMPO.\" \/ \"B. TIEMPO FMO.\" \/ \"MUY SECO.\""
  evidence: "Aspecto geral do objeto; textos impressos legíveis no mostrador; pequenas perdas visíveis na moldura à esquerda e à direita."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Torneado","Entalhe","Metal","Vidro","Impressão"]
  evidence: ["Moldura escura de aparência de madeira","Perfis concêntricos da moldura aparentemente torneados","Friso de dentes entalhados na moldura","Argola, aro e mecanismo metálicos","Vidro cobrindo o mostrador","Escalas e legendas impressas no mostrador"]
- inbcm-conservacao: "Regular"
  evidence: "Pequenas perdas e lascas no acabamento da moldura (bordas esquerda e direita), manchas no mostrador e no vidro; leitura do objeto preservada."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento associado à meteorologia"
  evidence: "Tesauro: classe de Barômetro"
  tesauro: names=["Barômetro","Barômetro aneroide com moldura circular de madeira escurecida"] -> ["Barômetro (exact 1)"]

## c07: 1 view (claude-sonnet-5-5; in 6259, out 1316, thinking 646, stop; system prompt 9117 chars; images 1600x1067; 12.8 s)
**Denominação** ✓ (expected "Gramofone") — **Classificação** ✓ 

- inbcm-denominacao: "Gramofone"
  evidence: "Tesauro (exact: \"Gramofone\"): Equipamento de comunicação › Equipamento de comunicação sonora › Fonógrafo › Gramofone"
- titulo-2: "Gramofone com corneta de metal pintada de vermelho"
  evidence: "Corneta com faixas vermelhas e douradas ou prateadas sobre caixa de madeira escura. Nenhum texto legível indica função ou homenageado."
- inbcm-autor: null
- descricao-2: "Gramofone de caixa quadrada em madeira escura, com base moldurada e prato giratório circular sobre o tampo. Braço curvo e corneta grande em metal, aparentemente pintada em vermelho e em faixas alternadas douradas ou prateadas, com ornamento em relevo na borda. Há um pequeno botão metálico circular na lateral da caixa. A pintura da corneta apresenta desgaste e perdas, e a madeira mostra marcas de uso."
  evidence: "Caixa de madeira, prato circular, braço curvo e corneta de metal com tinta vermelha descascada. Não há texto legível no objeto."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Metal","Pintura"]
  evidence: ["Caixa e base em madeira escura com acabamento envernizado.","Corneta, braço e prato aparentemente em metal.","Corneta com tinta vermelha e faixas douradas ou prateadas, com perdas visíveis."]
- inbcm-conservacao: "Regular"
  evidence: "Tinta da corneta com desgaste e perdas, e madeira da base com marcas de uso. O objeto está íntegro e legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação sonora"
  evidence: "Tesauro: classe de Gramofone"
  tesauro: names=["Gramofone","Gramofone com corneta de metal pintada de vermelho"] -> ["Gramofone (exact 1)"]

## c08: 1 view (claude-sonnet-5-5; in 6259, out 1338, thinking 643, stop; system prompt 9117 chars; images 1600x1065; 13 s)
**Denominação** ✓ (expected "Pião") — **Classificação** ✓ 

- inbcm-denominacao: "Pião"
  evidence: "Tesauro (exact: \"Pião\"): Equipamentos de lazer e esporte › Equipamento lúdico › Pião"
- titulo-2: "Pião de metal com listras coloridas e cabo de madeira"
  evidence: "Corpo metálico com listras creme, rosa e cinza-azuladas; cabo superior de madeira marrom."
- inbcm-autor: null
- descricao-2: "Pião de corpo discoidal em metal aparentemente decorado com impressão, com listras em creme, rosa e cinza-azulado. A face superior é verde, com figuras impressas, e o aro é prateado, com pequenas aberturas ovais. A ponta inferior é rosa metálica, com furos circulares. No topo, uma haste em espiral sustenta um cabo de madeira marrom. Há desgaste da pintura, arranhões e marcas nas bordas."
  evidence: "Corpo metálico listrado, face superior verde com figuras, aro prateado com aberturas, ponta rosa com furos, cabo de madeira; desgaste visível na pintura e no aro."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira"]
  evidence: ["Corpo e haste com aspecto metálico, com aro prateado e ponta rosa metálica.","Cabo superior de madeira marrom torneada."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste e perdas leves de pintura, arranhões e marcas ao longo das listras e do aro prateado, sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento lúdico"
  evidence: "Tesauro: classe de Pião"
  tesauro: names=["Pião","Pião de metal com listras coloridas e cabo de madeira"] -> ["Pião (exact 1)"]

## c09: 1 view (claude-sonnet-5-5; in 5389, out 1971, thinking 978, stop; system prompt 9117 chars; images 1600x656; 16.2 s)
**Denominação** ✓ (expected "Cédula") — **Classificação** ✓ 

- inbcm-denominacao: "Cédula"
  evidence: "Tesauro (exact: \"Cédula\"): Objetos pecuniários › Dinheiro › Cédula"
- titulo-2: "Cédula de um mil réis com retrato masculino e brasão"
  evidence: "Texto impresso 'UM MIL REIS'; retrato oval central e brasão com coroa à esquerda."
- inbcm-autor: ["American Bank Note Co.New York"]
  evidence: ["Impresso na margem inferior central: 'American Bank Note Co.New York'."]
- descricao-2: "Cédula retangular em papel aparentemente impresso, em preto com detalhes em verde-azulado claro e numeração em vermelho. Ao centro, retrato oval de um homem de barba longa. À esquerda, brasão com coroa e ramos; à direita, figura feminina sentada. Apresenta dobras, manchas, bordas desgastadas e pequena rasgadura na borda superior.\n\nInscrições: impresso, na parte superior: \"IMPERIO DO BRASIL \/ NO THESOURO NACIONAL\"; impresso, abaixo: \"SERIE\" e \"ESTAMPA\", com \"53ª[?]\" e \"6ª[?]\" e número \"2413\" em vermelho; impresso, em ambos os lados do retrato: \"Se pagará \/ desta a \/ ao portador \/ quantia de\" e \"UM MIL REIS\"; impresso, canto inferior esquerdo: \"No. 2413\" em vermelho; impresso, abaixo do retrato: \"VALOR \/ RECEBIDO\"; impresso, margem inferior: \"American Bank Note Co.New York\"; manuscrito, parte inferior: assinatura [ilegível]; manuscrito, à direita: \"A\"."
  evidence: "Texto impresso e numeração em vermelho visíveis na cédula; assinatura manuscrita em tinta escura sob o retrato; dobras, manchas e rasgadura na borda superior."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Papel","Impressão","Escrita manual"]
  evidence: ["Suporte em papel com aspecto de cédula, envelhecido e dobrado.","Texto, gravuras ornamentais e numeração aparentemente impressos.","Assinatura e letra 'A' manuscritas em tinta escura."]
- inbcm-conservacao: "Regular"
  evidence: "Dobras marcadas, manchas, bordas desgastadas e pequena rasgadura no topo, sem comprometer a leitura."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Cédula"
  tesauro: names=["Cédula","Cédula de um mil réis com retrato masculino e brasão"] -> ["Cédula (exact 1)"]

## c10: 1 view (claude-sonnet-5-5; in 6491, out 604, thinking 0, stop; system prompt 9117 chars; images 1200x1600; 8.2 s)
**Denominação** ✓ (expected "Estribo") — **Classificação** ✓ 

- inbcm-denominacao: "Estribo"
  evidence: "Tesauro (exact: \"Estribo\"): Veículos e acessórios › Veículos terrestres e acessórios › Arreio › Estribo"
- titulo-2: "Estribo de metal dourado com base vazada e estrela central"
  evidence: "Arco liso, base com orifícios em formato radial e frente com relevo vazado de estrela circular central."
- inbcm-autor: null
- descricao-2: "Estribo de metal de cor dourada a acobreada, aparentemente fundido. O arco superior é liso e termina em uma haste torneada com pequena saliência no topo. A base tem plataforma oval com orifícios radiais. A lateral frontal tem relevo vazado com estrela de cinco pontas, círculo central, ramos e figuras laterais. Apresenta desgaste e oxidação na superfície."
  evidence: "Forma, cor e acabamento do metal, relevo vazado da face frontal e desgaste visível nas superfícies."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Fundição"]
  evidence: ["Corpo inteiro com aspecto metálico de cor dourada a acobreada.","Relevos e vazados sugerem peça aparentemente fundida em metal."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste e oxidação visíveis na superfície; sem perdas ou fraturas evidentes que comprometam o objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Veículos terrestres e acessórios"
  evidence: "Tesauro: classe de Estribo"
  tesauro: names=["Estribo","Estribo de metal dourado com base vazada e estrela central"] -> ["Estribo (exact 1)"]

## c11: 1 view (claude-sonnet-5-5; in 6085, out 691, thinking 0, stop; system prompt 9117 chars; images 1600x983; 8 s)
**Denominação** ✓ (expected "Pistola") — **Classificação** ✓ 

- inbcm-denominacao: "Pistola"
  evidence: "Tesauro (exact: \"Pistola\"): Equipamentos de caça e de guerra › Armas e acessórios › Arma de fogo › Pistola"
- titulo-2: "Pistola de percussão com coronha de madeira entalhada"
  evidence: "Coronha de madeira com sulcos e motivos entalhados; mecanismo com cão e cano metálico oxidado. Nenhum texto inscrito visível."
- inbcm-autor: null
- descricao-2: "Pistola de cano único, com cano de metal oxidado e mecanismo com cão e fecho metálico escurecido. A coronha, de madeira aparentemente entalhada, é curva e apresenta faixas oblíquas com motivos incisos. A madeira tem tons de marrom e vermelho, com manchas e áreas desgastadas. O guarda-mato e a capa da extremidade da coronha são de metal claro. Há corrosão no cano e perdas ou desgaste na superfície da madeira."
  evidence: "Cano com ferrugem, madeira com manchas e desgaste, coronha com decoração incisa em faixas, guarda-mato e capa em metal claro."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira","Metal","Entalhe"]
  evidence: ["Coronha e cabo anterior em madeira de tons marrons","Cano, fecho, cão e guarda-mato metálicos","Coronha com motivos aparentemente entalhados em faixas oblíquas"]
- inbcm-conservacao: "Regular"
  evidence: "Corrosão visível no cano e no fecho, manchas e desgaste na madeira, pequena perda ou abertura na madeira do fuste; objeto íntegro e legível."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Armas e acessórios"
  evidence: "Tesauro: classe de Pistola"
  tesauro: names=["Pistola","Pistola de percussão com coronha de madeira entalhada"] -> ["Pistola (exact 1)"]

## c12: 1 view (claude-sonnet-5-5; in 6259, out 1206, thinking 647, stop; system prompt 9117 chars; images 1600x1067; 12.6 s)
**Denominação** ✗ (expected "Gargalheira") — **Classificação** ✓ 

- inbcm-denominacao: "Grilhão"
  evidence: "Tesauro (exact: \"Grilhão\"): Instrumentos de punição › Grilhão"
- titulo-2: "Grilhão de metal escuro com argola e placa"
  evidence: "Aro em faixa de metal com argola circular e placa plana com abertura, sem texto inscrito"
- inbcm-autor: null
- descricao-2: "Objeto de metal escuro, aparentemente ferro forjado, formado por uma faixa curva aberta em formato de aro. Em uma extremidade há uma articulação com pequeno gancho e, na outra, uma argola circular unida a uma placa plana com abertura retangular. A superfície é irregular, com marcas de martelamento e pequenas corrosões, e apresenta pátina marrom-escura."
  evidence: "Faixa em aro, argola e placa com fenda; superfície rugosa e escurecida em toda a peça"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Peça escura com superfície rugosa e aspecto de ferro oxidado"]
- inbcm-conservacao: "Regular"
  evidence: "Peça íntegra, mas com superfície corroída, com pequenas irregularidades e pátina escurecida"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Instrumentos de punição"
  evidence: "Tesauro: classe de Grilhão"
  tesauro: names=["Grilhão","Grilhão de metal escuro com argola e placa"] -> ["Grilhão (exact 1)"]

## c13: 1 view (claude-sonnet-5-5; in 6259, out 1471, thinking 892, stop; system prompt 9117 chars; images 1600x1065; 14.7 s)
**Denominação** ✗ (expected "Fragmento") — **Classificação** ✗ (expected "Amostras e fragmentos")

- inbcm-denominacao: "Relevo"
  evidence: "Tesauro (exact: \"Relevo\"): Objetos de atividades artísticas › Objetos associados às artes plásticas e ao desenho técnico › Escultura › Relevo"
- titulo-2: "Fragmento de relevo com buquê de flores amarrado por laço"
  evidence: "Motivo central: flores e folhas reunidas por uma fita ou laço, com bordas fraturadas."
- inbcm-autor: null
- descricao-2: "Fragmento de relevo de superfície marrom, com contorno irregular e bordas quebradas. Representa um buquê de flores e folhas amarrado por uma fita com laço, de pontas onduladas. Uma flor em forma de rosa, com orifício central, destaca-se na parte superior. A superfície apresenta pequenas perdas nas bordas e nas pétalas, além de pontos esbranquiçados que parecem resíduos."
  evidence: "Observação direta do objeto: buquê em relevo, fita amarrando os caules à esquerda, bordas lascadas e manchas brancas."
- inbcm-dimensoes: null
- inbcm-mattecnica: []
  evidence: null
- inbcm-conservacao: "Ruim"
  evidence: "Fragmento com bordas fraturadas, lascas e perdas nas pétalas e na fita; pontos esbranquiçados na superfície. Na dúvida entre dois níveis, adotou-se o pior."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos associados às artes plásticas e ao desenho técnico"
  evidence: "Tesauro: classe de Relevo"
  tesauro: names=["Relevo","Fragmento de relevo com buquê de flores amarrado por laço"] -> ["Relevo (exact 1)"]

## c14: 1 view (claude-sonnet-5-5; in 6491, out 1471, thinking 849, stop; system prompt 9117 chars; images 1200x1600; 14.4 s)
**Denominação** ✓ (expected "Violão") — **Classificação** ✓ 

- inbcm-denominacao: "Violão"
  evidence: "Tesauro (exact: \"Violão\"): Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Cordofone › Violão"
- titulo-2: "Violão de madeira com filetes decorativos em xadrez"
  evidence: "Borda do tampo e roseta da boca com filetes em padrão xadrez; escala com padrão quadriculado."
- inbcm-autor: null
- descricao-2: "Violão de caixa em forma de oito, aparentemente de madeira, com tampo liso em tom marrom-alaranjado. A borda do tampo e a roseta da boca têm filetes em padrão xadrez de tons claros e escuros. A escala tem decoração quadriculada e a cabeça apresenta seis furos, sem cravelhas. O instrumento não tem cordas, e o braço aparenta estar solto ou fraturado junto à boca. A ponte é de madeira, com inserção clara."
  evidence: "Corpo, tampo, boca, braço, cabeça com furos vazios e ponte sem cordas visíveis; junção do braço com a boca aparenta separação."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira"]
  evidence: ["Tampo, braço e cabeça com aspecto de madeira com veios visíveis."]
- inbcm-conservacao: "Ruim"
  evidence: "Sem cordas e sem cravelhas; braço aparentemente separado do corpo junto à boca, o que compromete o objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos associados à música"
  evidence: "Tesauro: classe de Violão"
  tesauro: names=["Violão","Violão de madeira com filetes decorativos em xadrez"] -> ["Violão (exact 1)"]

## c15: 1 view (claude-sonnet-5-5; in 6259, out 613, thinking 0, stop; system prompt 9117 chars; images 1600x1065; 7.4 s)
**Denominação** ✓ (expected "Palmatória (castiçal)", or Castiçal) — **Classificação** ✓ 

- inbcm-denominacao: "Castiçal"
  evidence: "Tesauro (exact: \"Castiçal\"): Objetos domésticos › Objetos e equipamentos de iluminação › Castiçal"
- titulo-2: "Castiçal de metal com prato circular e alça em argola"
  evidence: "Prato circular raso com soquete central fendido e alça lateral enrolada, em metal de aspecto acobreado."
- inbcm-autor: null
- descricao-2: "Castiçal de mão com prato circular raso de borda levemente elevada e soquete central cilíndrico, com fenda vertical. Possui alça lateral em fita de metal dobrada em argola. Metal aparentemente acobreado, com superfície escurecida e manchas em tons de vermelho, marrom e cinza-azulado. Há desgaste e oxidação na superfície e resíduos escuros no interior do soquete."
  evidence: "Prato com soquete central fendido, alça enrolada, superfície com manchas avermelhadas e acinzentadas, resíduo escuro no fundo do soquete."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Objeto de aspecto metálico acobreado, com prato, soquete e alça em chapa metálica."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas, oxidação e escurecimento da superfície, com resíduos no soquete; sem fraturas ou perdas aparentes que comprometam o objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de iluminação"
  evidence: "Tesauro: classe de Castiçal"
  tesauro: names=["Castiçal","Castiçal de metal com prato circular e alça em argola"] -> ["Castiçal (exact 1)"]

## c16: 1 view (claude-sonnet-5-5; in 6491, out 627, thinking 0, stop; system prompt 9117 chars; images 1200x1600; 7.7 s)
**Denominação** ✗ (expected "Aldrava") — **Classificação** ✓ 

- inbcm-denominacao: "Espelho de fechadura"
  evidence: "Tesauro (exact: \"Espelho de fechadura\"): Estruturas edificadas e elementos de construção › Elementos de construção › Ferragem de esquadria › Fechadura (janela\/porta) › Espelho de fechadura"
- titulo-2: "Espelho de fechadura de metal oxidado com argola"
  evidence: "Placa recortada com abertura de fechadura central e argola de metal presa à parte superior, com superfície oxidada"
- inbcm-autor: null
- descricao-2: "Espelho de fechadura de metal aparentemente ferro, com contorno recortado e orifício central em forma de buraco de fechadura. Apresenta pequenos furos para fixação e remate superior em forma de folhagem. Uma argola de metal, de haste torcida, está presa na parte superior, e uma haste cilíndrica aparece atrás da placa. A superfície tem corrosão marrom-alaranjada e restos de uma camada clara descascada."
  evidence: "Placa com furos e abertura central; argola presa no topo; haste ao fundo; superfície com ferrugem e resíduos de tinta clara em desgaste"
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Superfície metálica com corrosão marrom-alaranjada típica de oxidação, em placa recortada e argola"]
- inbcm-conservacao: "Ruim"
  evidence: "Corrosão generalizada com perdas da camada superficial clara e superfície irregular; deterioração comprometendo a integridade do metal"
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Elementos de construção"
  evidence: "Tesauro: classe de Espelho de fechadura"
  tesauro: names=["Espelho de fechadura","Espelho de fechadura de metal oxidado com argola"] -> ["Espelho de fechadura (exact 1)"]

## m1: 1 view (claude-sonnet-5-5; in 6201, out 1580, thinking 850, stop; system prompt 9117 chars; images 1600x1064; 15.1 s)
**Denominação** ✓ (expected "Sextante") — **Classificação** ✓ 

- inbcm-denominacao: "Sextante"
  evidence: "Tesauro (exact: \"Sextante\"): Equipamentos de atividades científicas e tecnológicas › Equipamentos associados à topografia e à navegação › Instrumento a reflexão › Sextante"
- titulo-2: "Sextante de metal com arco graduado e cabo de madeira"
  evidence: "Aspecto geral do objeto: armação metálica escura, arco graduado dourado e cabo de madeira torneada com mostrador circular."
- inbcm-autor: null
- descricao-2: "Sextante com armação em metal escurecido e arco graduado em metal de tom dourado. Tem espelhos e filtros de vidro, tubo de visada, lente de aumento e parafusos aparentemente de latão. O cabo é de madeira aparentemente torneada, com um mostrador circular de fundo branco e ponteiros. A superfície metálica apresenta manchas e oxidação.\n\nInscrições: gravado, na borda inferior do arco graduado: \"[ilegível] Southampton[?]\"; escala numérica gravada ao longo do arco."
  evidence: "Inscrição gravada na parte inferior do arco, à direita da base, parcialmente legível; escala gravada no arco; aparência de metal oxidado e cabo de madeira com mostrador."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal","Madeira","Vidro","Gravação"]
  evidence: ["Armação escura e arco dourado em metal, parafusos de latão aparente.","Cabo marrom avermelhado, de madeira aparentemente torneada.","Espelhos, filtros e lente de vidro visíveis.","Escala numérica e texto gravados no arco graduado."]
- inbcm-conservacao: "Regular"
  evidence: "Manchas e oxidação visíveis na superfície metálica, sem perdas aparentes que comprometam a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamentos associados à topografia e à navegação"
  evidence: "Tesauro: classe de Sextante"
  tesauro: names=["Sextante","Sextante de metal com arco graduado e cabo de madeira"] -> ["Sextante (exact 1)"]

## m2: 1 view (claude-sonnet-5-5; in 7361, out 1482, thinking 687, stop; system prompt 9117 chars; images 1600x1600; 15 s)
**Denominação** ✓ (expected "Moeda") — **Classificação** ✓ 

- inbcm-denominacao: "Moeda"
  evidence: "Tesauro (exact: \"Moeda\"): Objetos pecuniários › Dinheiro › Moeda"
- titulo-2: "Moeda de metal prateado com inscrição 2000 RÉIS"
  evidence: "Texto em relevo \"2000 RÉIS\" ao centro e \"ORDEM E PROGRESSO\" na orla; cor prateada."
- inbcm-autor: null
- descricao-2: "Moeda circular de metal de cor prateada, com relevo em ambas as áreas de campo e orla de pontos ou serrilha. Na metade superior há um emblema com estrela de cinco pontas sobre raios, ramos e faixa curva com texto. Na metade inferior, o valor aparece em algarismos grandes entre dois ramos de folhas e frutos. Apresenta desgaste generalizado, riscos e uma pequena deformação da borda no alto à esquerda.\n\nInscrições: em relevo, na orla, à esquerda e à direita: \"ORDEM E \/ PROGRESSO\"; em relevo, na faixa do emblema, parcialmente legível: \"[ilegível]NOVE[?] [ilegível] 1889[?]\"; em relevo, ao centro inferior: \"2000 \/ RÉIS\"."
  evidence: "Observação do objeto: relevo, orla perolada, desgaste e leve irregularidade da borda superior esquerda; texto em relevo na orla, na faixa e na parte central."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Metal"]
  evidence: ["Superfície metálica de cor prateada, com brilho e desgaste típicos de metal."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste do relevo, riscos e pequena irregularidade na borda superior esquerda, sem comprometer a leitura das inscrições."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Dinheiro"
  evidence: "Tesauro: classe de Moeda"
  tesauro: names=["Moeda","Moeda de metal prateado com inscrição 2000 RÉIS"] -> ["Moeda (exact 1)"]

## m3: 1 view (claude-sonnet-5-5; in 6201, out 1129, thinking 487, stop; system prompt 9117 chars; images 1600x1064; 12.4 s)
**Denominação** ✓ (expected "Carimbo", or Carimbo (selo)) — **Classificação** ✓ 

- inbcm-denominacao: "Carimbo"
  evidence: "Tesauro (exact: \"Carimbo\"): Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo. Alternativas: Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Carimbo › Carimbo (selo); Carimbo (selo) — Equipamento de comunicação › Equipamento de comunicação escrita › Matriz de selo › Carimbo (selo)"
- titulo-2: "Carimbo de madeira com cabo preto e base vermelha"
  evidence: "Cabo preto torneado sobre base retangular em tom vermelho-amarronzado."
- inbcm-autor: null
- descricao-2: "Carimbo manual com cabo alongado e bulboso, de acabamento preto brilhante, fixado sobre base retangular de madeira aparentemente envernizada em tom vermelho-amarronzado. O topo do cabo apresenta desgaste da tinta e pequenas manchas. A base tem pequenas perdas e arranhões nas bordas, e a lateral direita, em madeira mais clara, traz marcas desgastadas.\n\nInscrições: lateral direita da base, marca desgastada: [ilegível]"
  evidence: "Cabo preto com tinta gasta no topo; base vermelha com lascas nas bordas; lateral direita da base com marcas de texto apagadas e ilegíveis."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Madeira"]
  evidence: ["Cabo e base com aspecto de madeira, com veios visíveis na base e acabamento envernizado ou pintado."]
- inbcm-conservacao: "Regular"
  evidence: "Desgaste da tinta no topo do cabo, pequena perda junto à base do cabo, arranhões e lascas nas bordas da base; o objeto continua legível e íntegro."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Equipamento de comunicação escrita"
  evidence: "Tesauro: classe de Carimbo"
  tesauro: names=["Carimbo","Carimbo de madeira com cabo preto e base vermelha"] -> ["Carimbo (exact 1)","Carimbo (selo) (exact 0.98)","Carimbo (selo) (exact 0.98)"]

## m4: 1 view (claude-sonnet-5-5; in 6665, out 654, thinking 0, stop; system prompt 9117 chars; images 1600x1288; 8.1 s)
**Denominação** ✓ (expected "Prato", or Prato decorativo) — **Classificação** ✓ 

- inbcm-denominacao: "Prato"
  evidence: "Tesauro (exact: \"Prato\"): Objetos domésticos › Objetos e equipamentos de serviço de alimentos › Prato. Alternativas: Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Idiofone › Prato (música); Prato (música) — Objetos de atividades artísticas › Objetos associados à música › Instrumento musical › Membranofone › Bateria › Prato (música)"
- titulo-2: "Prato branco com pintura floral e filete azul na borda"
  evidence: "Superfície branca com guirlanda de rosas e margaridas pintadas; filete azul no contorno externo."
- inbcm-autor: null
- descricao-2: "Prato circular de superfície branca e brilhante, aparentemente de porcelana ou cerâmica esmaltada. Guirlanda de quatro rosas rosa-magenta alternadas com flores azuis, laranja e amarelas e folhas verdes, aparentemente pintada à mão, circunda o centro liso. Filete azul fino contorna a borda. Há pequenos pontos de desgaste ou perda de esmalte na borda, à esquerda e na parte inferior."
  evidence: "Pinceladas visíveis nas flores; filete azul na borda; pequenos pontos marrons na borda inferior esquerda e no alto à direita. Nenhum texto visível."
- inbcm-dimensoes: null
- inbcm-mattecnica: ["Porcelana","Pintura"]
  evidence: ["Corpo branco, liso e brilhante, aparentemente de porcelana; o material não é totalmente certo.","Flores e filete aparentemente pintados à mão, com pinceladas visíveis."]
- inbcm-conservacao: "Regular"
  evidence: "Pequenas perdas ou manchas na borda (inferior esquerda e superior direita), sem comprometer a leitura do objeto."
- inbcm-localprod: null
- inbcm-dataprod: null
- inbcm-classificacao: "Objetos e equipamentos de serviço de alimentos"
  evidence: "Tesauro: classe de Prato"
  tesauro: names=["Prato","Prato branco com pintura floral e filete azul na borda"] -> ["Prato (exact 1)","Prato (música) (exact 0.98)","Prato (música) (exact 0.98)"]

