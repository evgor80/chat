<?php

namespace App\Utils;

class MessageSanitizer
{
    private static $emojis = array(
        ["name" => 'смеющееся лицо с большими глазами', "alt" => '😃'],
        ["name" => 'хохочущее лицо со смеющимися глазами', "alt" => '😄'],
        ["name" => 'сияющее лицо со смеющимися глазами', "alt" => '😁'],
        ["name" => 'хохочущее лицо с сощуренными глазами', "alt" => '😆'],
        ["name" => 'лицо, катающееся по полу от смеха', "alt" => '🤣'],
        ["name" => 'лицо со слезами радости', "alt" => '😂'],
        ["name" => 'лицо с легкой улыбкой', "alt" => '🙂'],
        ["name" => 'лицо, перевернутое вверх ногами', "alt" => '🙃'],
        ["name" => 'подмигивающее лицо', "alt" => '😉'],
        ["name" => 'улыбающееся лицо', "alt" => '☺️'],
        ["name" => 'улыбающееся лицо с нимбом', "alt" => '😇'],
        ["name" => 'улыбающееся лицо с сердечками', "alt" => '🥰'],
        ["name" => 'улыбающееся лицо с глазами в виде сердечек', "alt" => '😍'],
        ["name" => 'лицо со звездами вместо глаз', "alt" => '🤩'],
        ["name" => 'воздушный поцелуй', "alt" => '😘'],
        ["name" => 'целующее лицо', "alt" => '😗'],
        ["name" => 'целующее лицо с закрытыми глазами', "alt" => '😚'],
        ["name" => 'улыбка сквозь слезы', "alt" => '🥲'],
        ["name" => 'лицо смакующее еду', "alt" => '😋'],
        ["name" => 'лицо, показывающее язык', "alt" => '😛'],
        ["name" => 'подмигывающее лицо, показывающее язык', "alt" => '😜'],
        ["name" => 'дурачащееся лицо', "alt" => '🤪'],
        ["name" => 'лицо с сощуренными глазами и высунутым языком', "alt" => '😝'],
        ["name" => 'лицо с долларами в глазах', "alt" => '🤑'],
        ["name" => 'обнимашки', "alt" => '🤗'],
        ["name" => 'лицо прикрывающее рот рукой', "alt" => '🤭'],
        ["name" => 'шикающее лицо', "alt" => '🤫'],
        ["name" => 'размышляющее лицо', "alt" => '🤔'],
        ["name" => 'рот на замке', "alt" => '🤐'],
        ["name" => 'лицо с приподнятой бровью', "alt" => '🤨'],
        ["name" => 'недовольное лицо', "alt" => '😒'],
        ["name" => 'лицо закатившее глаза', "alt" => '🙄'],
        ["name" => 'гримасничающее лицо', "alt" => '😬'],
        ["name" => 'обманщик', "alt" => '🤥'],
        ["name" => 'лицо, испытывающее облегчение', "alt" => '😌'],
        ["name" => 'меланхоличное лицо', "alt" => '😞'],
        ["name" => 'сонное лицо', "alt" => '😪'],
        ["name" => 'нейтральное лицо', "alt" => '😐'],
        ["name" => 'лицо без рта', "alt" => '😶'],
        ["name" => 'ухмыляющееся лицо', "alt" => '😏'],
        ["name" => 'лицо, пускающее слюни', "alt" => '🤤'],
        ["name" => 'спящее лицо', "alt" => '😴'],
        ["name" => 'лицо в медицинской маске', "alt" => '😷'],
        ["name" => 'лицо с термометром', "alt" => '🤒'],
        ["name" => 'лицо с повязкой на голове', "alt" => '🤕'],
        ["name" => 'лицо, испытывающее тошноту', "alt" => '🤢'],
        ["name" => 'лицо которое тошнит', "alt" => '🤮'],
        ["name" => 'чихающее лицо', "alt" => '🤧'],
        ["name" => 'распаренное лицо', "alt" => '🥵'],
        ["name" => 'замерзшее лицо', "alt" => '🥶'],
        ["name" => 'растерянное лицо', "alt" => '😕'],
        ["name" => 'нетрезвое лицо', "alt" => '🥴'],
        ["name" => 'ошеломленное лицо', "alt" => '😵'],
        ["name" => 'взрыв мозга', "alt" => '🤯'],
        ["name" => 'праздник', "alt" => '🥳'],
        ["name" => 'лицо в ковбойской шляпе', "alt" => '🤠'],
        ["name" => 'улыбающееся лицо в солнцезащитных очках', "alt" => '😎'],
        ["name" => 'умник', "alt" => '🤓'],
        ["name" => 'лицо с моноклем', "alt" => '🧐'],
        ["name" => 'озабоченное лицо', "alt" => '😟'],
        ["name" => 'слегка нахмуренное лицо', "alt" => '🙁'],
        ["name" => 'хмурое лицо', "alt" => '☹️'],
        ["name" => 'лицо с открытым ртом', "alt" => '😮'],
        ["name" => 'притихшее лицо', "alt" => '😯'],
        ["name" => 'удивленное лицо', "alt" => '😲'],
        ["name" => 'покрасневшее лицо', "alt" => '😳'],
        ["name" => 'умоляющее лицо', "alt" => '🥺'],
        ["name" => 'хмурое лицо с открытым ртом', "alt" => '😦'],
        ["name" => 'страдальческое лицо', "alt" => '😧'],
        ["name" => 'испуганное лицо', "alt" => '😨'],
        ["name" => 'встревоженное лицо', "alt" => '😰'],
        ["name" => 'грустное, но испытывающее облегчение лицо', "alt" => '😥'],
        ["name" => 'плачущее лицо', "alt" => '😢'],
        ["name" => 'громко рыдающее лицо', "alt" => '😭'],
        ["name" => 'лицо, кричащее от страха', "alt" => '😱'],
        ["name" => 'смущенное лицо', "alt" => '😖'],
        ["name" => 'упорствующее лицо', "alt" => '😣'],
        ["name" => 'разочарованное лицо', "alt" => '😞'],
        ["name" => 'удрученное лицо', "alt" => '😓'],
        ["name" => 'утомленное лицо', "alt" => '😩'],
        ["name" => 'усталое лицо', "alt" => '😫'],
        ["name" => 'зевающее лицо', "alt" => '🥱'],
        ["name" => 'лицо с паром из ноздрей', "alt" => '😤'],
        ["name" => 'надутое лицо, кривящее губы', "alt" => '😡'],
        ["name" => 'злое лицо', "alt" => '😠'],
        ["name" => 'лицо, выражающееся нецензурно', "alt" => '🤬'],
        ["name" => 'какашка', "alt" => '💩'],
        ["name" => 'чертик со злым лицом', "alt" => '👿'],
        ["name" => 'улыбающийся чертенок', "alt" => '😈'],
        ["name" => 'смеющаяся кошачья мордочка', "alt" => '😺'],
        ["name" => 'хохочущая кошачья мордочка со смеющимися глазами', "alt" => '😸'],
        ["name" => 'кошачья мордочка со слезами радости', "alt" => '😹'],
        ["name" => 'смеющаяся кошачья мордочка с глазами в виде сердечек', "alt" => '😻'],
        ["name" => 'ухмыляющаяся кошачья мордочка', "alt" => '😼'],
        ["name" => 'кошачья мордочка, посылающая поцелуй', "alt" => '😽'],
        ["name" => 'изумленная кошачья мордочка', "alt" => '🙀'],
        ["name" => 'плачущая кошачья мордочка', "alt" => '😿'],
        ["name" => 'хмурая кошачья мордочка', "alt" => '😾'],
        ["name" => 'ок', "alt" => '👌'],
        ["name" => 'знак победы', "alt" => '✌️'],
        ["name" => 'скрещенные на удачу пальцы', "alt" => '🤞'],
        ["name" => 'указательный палец, поднятый вверх', "alt" => '☝️'],
        ["name" => 'большой палец вверх', "alt" => '👍'],
        ["name" => 'большой палец вниз', "alt" => '👎'],
        ["name" => 'аплодисменты', "alt" => '👏'],
        ["name" => 'рукопожатие', "alt" => '🤝'],
    );

    private static $tags = [
        '&' => '&amp;',
        '<' => '&lt;',
        '>' => '&gt;',
        "'" => '&apos;',
        '"' => '&quot;'
    ];

    /**
     * Take given message replace all possible html tags with their safe versions and all smiles with corresponding images
     * 
     * @param string $message
     * @return string
     */
    public static function sanitize(string $message)
    {
        $message = trim($message);
        $sanitizedMessage = '';
        $emojis_pairs = [];
        //create array where key is emoji symbol and value is html image tag to insert into user's message
        foreach (self::$emojis as $i => $e) {
            $emojis_pairs[$e['alt']] = '<img src="/dist/emojis/' . ($i + 1)  . '.png" alt="' . $e['alt'] . '" width="30" height="30" ></img>';
        }
        $all = array_merge($emojis_pairs, self::$tags);
        //create regex patterns from emojis symbols and html tags that should be replased in a message
        $pattern = '/' . implode('|', array_keys($all)) . '/i';
        //replace found emoji symbols and html tags with their matchers from given array
        $sanitizedMessage = preg_replace_callback(
            $pattern,
            function ($matches) use ($all) {
                return implode("", array_map(function ($match) use ($all) {
                    return $all[$match];
                }, $matches));
            },
            $message
        );

        return $sanitizedMessage;
    }
}